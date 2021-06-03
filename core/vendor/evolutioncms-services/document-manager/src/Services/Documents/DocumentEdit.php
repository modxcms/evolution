<?php namespace EvolutionCMS\DocumentManager\Services\Documents;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentEdit extends DocumentCreate
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

        if (isset($this->documentData['pagetitle'])) {
            $this->prepareAliasDocument();
        }
        $this->prepareEditDocument();

        // invoke OnBeforeDocFormSave event
        if ($this->events) {
            EvolutionCMS()->invokeEvent("OnBeforeDocFormSave", array(
                "mode" => "upd",
                "user" => $this->documentData['id'],
            ));
        }

        $document = SiteContent::query()->withTrashed()->find($this->documentData['id']);
        $document->update($this->documentData);
        $this->prepareTV();
        $this->saveTVs();
        $this->updateParent();

        if ($this->events) {
            // invoke OnDocFormSave event
            EvolutionCMS()->invokeEvent("OnDocFormSave", array(
                "mode" => "upd",
                "id" => $this->documentData['id']
            ));
        }


        $_SESSION['itemname'] = $this->documentData['pagetitle'];

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
        $existingDocument = SiteContent::query()->withTrashed()->find($this->documentData['id'])->toArray();
        $this->documentData['editedby'] = EvolutionCMS()->getLoginUserID();
        $this->documentData['editedon'] = $this->currentDate;
        $this->documentData['oldparent'] = $existingDocument['parent'];
        if (!isset($this->documentData['parent'])) {
            $this->documentData['parent'] = $this->documentData['oldparent'];
        }
        if (!isset($this->documentData['template'])) {
            $this->documentData['template'] = $existingDocument['template'];
        }

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
        $child = \EvolutionCMS\Models\SiteContent::withTrashed()->select('id')->where('parent', $this->documentData['id'])->first();
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
            $this->documentData['publishedby'] = EvolutionCMS()->getLoginUserID();
        } elseif ($was_published && !$this->documentData['published']) {
            $this->documentData['publishedon'] = 0;
            $this->documentData['publishedby'] = 0;
        } else {
            $this->documentData['publishedon'] = $existingDocument['publishedon'];
            $this->documentData['publishedby'] = $existingDocument['publishedby'];
        }


    }

}
