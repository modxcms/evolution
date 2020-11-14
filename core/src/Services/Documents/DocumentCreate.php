<?php namespace EvolutionCMS\Services\Documents;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentCreate implements ServiceInterface
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
            'pagetitle' => ['required', 'unique:site_content'],
        ];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
            'pagetitle.required' => Lang::get("global.required_field", ['field' => 'pagetitle']),
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

        $this->prepareDocument();
        $this->prepareCreateDocument();
        // invoke OnBeforeDocFormSave event
        if ($this->events) {
            EvolutionCMS()->invokeEvent("OnBeforeDocFormSave", array(
                "mode" => "new",
                "user" => $this->documentData['id'],
            ));
        }

        $document = SiteContent::query()->create($this->documentData);
        $this->documentData['id'] = $document->getKey();

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

    public function prepareDocument()
    {
        $this->documentData['parent'] = (int)get_by_key($_POST, 'parent', 0, 'is_scalar');
        $this->documentData['menuindex'] = !empty($this->documentData['menuindex']) ? (int)$this->documentData['menuindex'] : 0;


        if (trim($this->documentData['pagetitle']) == "") {
            if ($this->documentData['type'] == "reference") {
                $this->documentData['pagetitle'] = Lang::get('global.untitled_weblink');
            } else {
                $this->documentData['pagetitle'] = Lang::get('global.untitled_resource');
            }
        }


        if (!isset($this->documentData['pub_date'])) {
            $this->documentData['pub_date'] = 0;
        } else {
            $this->documentData['pub_date'] = EvolutionCMS()->toTimeStamp($this->documentData['pub_date']);

            if ($this->documentData['pub_date'] < $this->currentDate) {
                $this->documentData['published'] = 1;
            } elseif ($this->documentData['pub_date'] > $this->currentDate) {
                $this->documentData['published'] = 0;
            }
        }

        if (!isset($this->documentData['unpub_date'])) {
            $this->documentData['unpub_date'] = 0;
        } else {
            $this->documentData['unpub_date'] = EvolutionCMS()->toTimeStamp($this->documentData['unpub_date']);
            if ($this->documentData['unpub_date'] < $this->currentDate) {
                $this->documentData['published'] = 0;
            }
        }

    }

    public function prepareCreateDocument()
    {
        $this->documentData['createdby'] = EvolutionCMS()->getLoginUserID('mgr');
        $this->documentData['createdon'] = $this->currentDate;
        // invoke OnBeforeDocFormSave event
        switch (EvolutionCMS()->getConfig('docid_incrmnt_method')) {
            case '1':
                $id = \EvolutionCMS\Models\SiteContent::query()
                    ->leftJoin('site_content as t1', 'site_content.id +1', '=', 't1.id')
                    ->whereNull('t1.id')->min('site_content.id');
                $id++;

                break;
            case '2':
                $id = \EvolutionCMS\Models\SiteContent::max('id');
                $id++;
                break;

            default:
                $id = '';
        }
        if ($id != '')
            $this->documentData['id'] = $id;

    }

    public function prepareTV()
    {
        $tmplvars = SiteTmplvarTemplate::query()->where('templateid', $this->documentData['template'])->get();
    }

    public function saveTVs()
    {
        // update template variables
        $tvs = \EvolutionCMS\Models\SiteTmplvarContentvalue::select('id', 'tmplvarid')->where('contentid', $this->documentData['id'])->get();
        $tvIds = array();
        foreach ($tvs as $tv) {
            $tvIds[$tv->tmplvarid] = $tv->id;
        }
        $tvDeletions = array();
        $tvChanges = array();
        $tvAdded = array();

        foreach ($this->tvs as $field => $value) {

            if (!is_array($value)) {
                if (isset($tvIds[$value])) $tvDeletions[] = $tvIds[$value];
            } else {
                $tvId = $value[0];
                $tvVal = $value[1];
                if (isset($tvIds[$tvId])) {
                    \EvolutionCMS\Models\SiteTmplvarContentvalue::query()->find($tvIds[$tvId])->update(array('tmplvarid' => $tvId, 'contentid' => $this->documentData['id'], 'value' => $tvVal));
                } else {
                    \EvolutionCMS\Models\SiteTmplvarContentvalue::query()->create(array('tmplvarid' => $tvId, 'contentid' => $this->documentData['id'], 'value' => $tvVal));
                }
            }
        }

        if (!empty($tvDeletions)) {
            \EvolutionCMS\Models\SiteTmplvarContentvalue::query()->whereIn('id', $tvDeletions)->delete();
        }
    }

    public function updateParent()
    {
        // update parent folder status
        if ($this->documentData['parent'] != 0) {
            $fields = array('isfolder' => 1);
            \EvolutionCMS\Models\SiteContent::where('id', $this->documentData['parent'])->update(['isfolder' => 1]);
        }
    }

}
