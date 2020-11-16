<?php namespace EvolutionCMS\Services\Documents;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentDuplicate extends DocumentCreate
{
    /**
     * @var \string[][]
     */
    public $validate;

    /**
     * @var array
     */
    public $messages;

    /**
     * @var array
     */
    public $documentData;

    /**
     * @var bool
     */
    public $events;

    /**
     * @var bool
     */
    public $cache;

    /**
     * @var array $validateErrors
     */
    public $validateErrors;

    /**
     * @var int
     */
    public $currentDate;

    /**
     * @var array
     */
    public $tvs = [];

    /**
     * UserRegistration constructor.
     * @param array $documentData
     * @param bool $events
     * @param bool $cache
     */
    public function __construct(array $documentData, bool $events = true, bool $cache = true)
    {
        $this->validate = $this->getValidationRules();
        $this->messages = $this->getValidationMessages();
        $this->documentData = $documentData;
        $this->events = $events;
        $this->cache = $cache;
        $this->currentDate = EvolutionCMS()->timestamp((int)get_by_key($_SERVER, 'REQUEST_TIME', 0));

    }

    /**
     * @return \string[][]
     */
    public function getValidationRules(): array
    {
        return [
            'id' => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
            'id.required' => Lang::get("global.required_field", ['field' => 'id']),
        ];

    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @throws ServiceActionException
     * @throws ServiceValidationException
     */
    public function process(): \Illuminate\Database\Eloquent\Model
    {
        if (!$this->checkRules()) {
            throw new ServiceActionException(\Lang::get('global.error_no_privileges'));
        }


        if (!$this->validate()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($this->validateErrors);
            throw $exception;
        }


        if ($this->events) {
            $evtOut = EvolutionCMS()->invokeEvent('OnBeforeDocDuplicate', array(
                'id' => $this->documentData['id']
            ));
        }
        $documentObject = SiteContent::query()->find($this->documentData['id']);
        $tvArray = $documentObject->tv->pluck('value', 'name')->toArray();

        $documentArray = array_merge($documentObject->toArray(), $tvArray);
        // Handle incremental ID
        switch (EvolutionCMS()->getConfig('docid_incrmnt_method')) {
            case '1':
                $minId = \EvolutionCMS\Models\SiteContent::query()
                    ->leftJoin('site_content as T1', \DB::raw('(') . 'site_content.id' . \DB::raw('+1)'), '=', 'T1.id')
                    ->whereNull('T1.id')->min('site_content.id');

                $documentArray['id'] = $minId + 1;
                break;
            case '2':
                $documentArray['id'] = \EvolutionCMS\Models\SiteContent::query()->max('id') + 1;
                break;

            default:
                unset($documentArray['id']); // remove the current id.
        }

        // Once we've grabbed the document object, start doing some modifications
        if (!isset($this->documentData['toplevel'])) {
            // count duplicates
            $pagetitle = \EvolutionCMS\Models\SiteContent::find($this->documentData['id'])->pagetitle;

            $count = \EvolutionCMS\Models\SiteContent::query()->where('pagetitle', 'LIKE', '%' . $pagetitle . ' ' . \Lang::get('global.duplicated_el_suffix') . '%')->count();

            if ($count >= 1) {
                $count = ' ' . ($count + 1);
            } else {
                $count = '';
            }

            $documentArray['pagetitle'] = $pagetitle . ' ' . \Lang::get('global.duplicated_el_suffix') . ' ' . $count;
            $documentArray['alias'] = null;
        } elseif (EvolutionCMS()->getConfig('friendly_urls') == 0 || EvolutionCMS()->getConfig('allow_duplicate_alias') == 0) {
            $documentArray['alias'] = null;
        }

        // change the parent accordingly
        if (isset($this->documentData['parent'])) {
            $documentArray['parent'] = $this->documentData['parent'];
        }
        $document = \DocumentManager::create($documentArray);

        $oldDocGroups = \EvolutionCMS\Models\DocumentGroup::query()->where('document', $this->documentData['id'])->get();
        foreach ($oldDocGroups->toArray() as $oldDocGroup) {
            unset($oldDocGroup['id']);
            $oldDocGroup['document'] = $document->getKey();
            \EvolutionCMS\Models\DocumentGroup::query()->insert($oldDocGroup);
        }
        if ($this->events) {
            $evtOut = EvolutionCMS()->invokeEvent('OnDocDuplicate', array(
                'id' => $this->documentData['id']
            ));
        }
        $documents = \EvolutionCMS\Models\SiteContent::where('parent', $this->documentData['id'])->where('deleted', 0)->orderBy('id')->get();

        foreach ($documents as $item) {
            \DocumentManager::duplicate(['id' => $item->id, 'parent' => $document->getKey(), 'toplevel' => 1]);
        }

        if ($this->cache) {
            EvolutionCMS()->clearCache('full');
        }

        return $document;
    }

    /**
     * @return bool
     */
    public function checkRules(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $validator = \Validator::make($this->documentData, $this->validate, $this->messages);
        $this->validateErrors = $validator->errors()->toArray();
        return !$validator->fails();
    }


    public function prepareEditDocument()
    {
        $existingDocument = SiteContent::query()->find($this->documentData['id'])->toArray();
        $this->documentData['editedby'] = EvolutionCMS()->getLoginUserID('mgr');
        $this->documentData['editedon'] = $this->currentDate;
        $this->documentData['oldparent'] = $existingDocument['parent'];

        if ($this->documentData['id'] == EvolutionCMS()->getConfig('site_start') && $this->documentData['published'] == 0) {
            throw new ServiceActionException("Document is linked to site_start variable and cannot be unpublished!");
        }
        $today = EvolutionCMS()->timestamp((int)get_by_key($_SERVER, 'REQUEST_TIME', 0));
        if ($this->documentData['id'] == EvolutionCMS()->getConfig('site_start') && ($this->documentData['pub_date'] > $today || $this->documentData['unpub_date'] != "0")) {
            throw new ServiceActionException("Document is linked to site_start variable and cannot have publish or unpublish dates set!");
        }
        if ($this->documentData['parent'] == $this->documentData['id']) {
            throw new ServiceActionException("Document can not be it's own parent!");
        }

        $parents = EvolutionCMS()->getParentIds($this->documentData['parent']);
        if (in_array($this->documentData['id'], $parents)) {
            throw new ServiceActionException("Document descendant can not be it's parent!");
        }

        // check to see document is a folder
        $child = \EvolutionCMS\Models\SiteContent::select('id')->where('parent', $this->documentData['id'])->first();
        if (!is_null($child)) {
            $this->documentData['isfolder'] = 1;
        }

        // set publishedon and publishedby
        $was_published = $existingDocument['published'];

        // keep original publish state, if change is not permitted
        if (!EvolutionCMS()->hasPermission('publish_document')) {
            $this->documentData['published'] = $was_published;
            $this->documentData['pub_date'] = $existingDocument['pub_date'];
            $this->documentData['unpub_date'] = $existingDocument['unpub_date'];
        }

        // if it was changed from unpublished to published
        if (!$was_published && $this->documentData['published']) {
            $this->documentData['publishedon'] = $this->currentDate;
            $this->documentData['publishedby'] = EvolutionCMS()->getLoginUserID();
        } elseif ((!empty($this->documentData['pub_date']) && $this->documentData['pub_date'] <= $this->currentDate && $this->documentData['published'])) {
            $this->documentData['publishedon'] = $this->documentData['pub_date'];
            $this->documentData['publishedby'] = EvolutionCMS()->getLoginUserID('mgr');
        } elseif ($was_published && !$this->documentData['published']) {
            $this->documentData['publishedon'] = 0;
            $this->documentData['publishedby'] = 0;
        } else {
            $this->documentData['publishedon'] = $existingDocument['publishedon'];
            $this->documentData['publishedby'] = $existingDocument['publishedby'];
        }


    }

}
