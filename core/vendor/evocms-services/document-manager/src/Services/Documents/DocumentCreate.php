<?php namespace EvolutionCMS\DocumentManager\Services\Documents;

use EvolutionCMS\DocumentManager\Interfaces\DocumentServiceInterface;
use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentCreate implements DocumentServiceInterface
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
            'pagetitle' => ['required'],
            'template' => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
            'pagetitle.required' => Lang::get("global.required_field", ['field' => 'pagetitle']),
            'template.required' => Lang::get("global.required_field", ['field' => 'template']),
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
        $this->prepareAliasDocument();
        $this->prepareCreateDocument();
        // invoke OnBeforeDocFormSave event
        if ($this->events) {
            EvolutionCMS()->invokeEvent("OnBeforeDocFormSave", array(
                "mode" => "new",
                "user" => $this->documentData['id'],
            ));
        }
//var_dump($this->documentData);
//        die();
        if (isset($this->documentData['pub_date']) && !is_numeric($this->documentData['pub_date'])) {
            unset($this->documentData['pub_date']);
        }
        if (isset($this->documentData['unpub_date']) && !is_numeric($this->documentData['unpub_date'])) {
            unset($this->documentData['unpub_date']);
        }
        $document = SiteContent::query()->withTrashed()->create($this->documentData);
        $this->documentData['id'] = $document->getKey();

        $this->prepareTV();
        $this->saveTVs();
        $this->updateParent();

        if ($this->events) {
            // invoke OnDocFormSave event
            EvolutionCMS()->invokeEvent("OnDocFormSave", array(
                "mode" => "new",
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
        if (!isset($this->documentData['alias'])) {
            $this->documentData['alias'] = false;
        }
        if (!isset($this->documentData['id'])) {
            $this->documentData['id'] = false;
        }

      
        if (isset($this->documentData['pagetitle']) && trim($this->documentData['pagetitle']) == "") {
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

    public function prepareAliasDocument()
    {

        // friendly url alias checks
        if (EvolutionCMS()->getConfig('friendly_urls')) {
            // auto assign alias
            if (!$this->documentData['alias'] && EvolutionCMS()->getConfig('automatic_alias')) {
                $this->documentData['alias'] = strtolower(EvolutionCMS()->stripAlias(trim($this->documentData['pagetitle'])));
                if (!EvolutionCMS()->getConfig('allow_duplicate_alias')) {

                    if (\EvolutionCMS\Models\SiteContent::query()
                            ->withTrashed()
                            ->where('id', '<>', $this->documentData['id'])
                            ->where('alias', $this->documentData['alias'])->count() > 0) {
                        $cnt = 1;
                        $tempAlias = $this->documentData['alias'];

                        while (\EvolutionCMS\Models\SiteContent::query()
                                ->withTrashed()
                                ->where('id', '<>', $this->documentData['id'])
                                ->where('alias', $tempAlias)->count() > 0) {
                            $tempAlias = $this->documentData['alias'];
                            $tempAlias .= $cnt;
                            $cnt++;
                        }
                        $this->documentData['alias'] = $tempAlias;
                    }
                } else {
                    if (\EvolutionCMS\Models\SiteContent::query()
                            ->withTrashed()
                            ->where('id', '<>', $this->documentData['id'])
                            ->where('alias', $this->documentData['alias'])
                            ->where('parent', $this->documentData['parent'])->count() > 0) {
                        $cnt = 1;
                        $tempAlias = $this->documentData['alias'];
                        while (\EvolutionCMS\Models\SiteContent::query()
                                ->withTrashed()
                                ->where('id', '<>', $this->documentData['id'])
                                ->where('alias', $tempAlias)
                                ->where('parent', $this->documentData['parent'])->count() > 0) {
                            $tempAlias = $this->documentData['alias'];
                            $tempAlias .= $cnt;
                            $cnt++;
                        }
                        $this->documentData['alias'] = $tempAlias;
                    }
                }
            } // check for duplicate alias name if not allowed
            elseif ($this->documentData['alias'] && !EvolutionCMS()->getConfig('allow_duplicate_alias')) {
                $this->documentData['alias'] = EvolutionCMS()->stripAlias($this->documentData['alias']);
                $docid = \EvolutionCMS\Models\SiteContent::query()
                    ->select('id')
                    ->withTrashed()
                    ->where('id', '<>', $this->documentData['id'])
                    ->where('alias', $this->documentData['alias']);
                if (EvolutionCMS()->getConfig('use_alias_path')) {
                    // only check for duplicates on the same level if alias_path is on
                    $docid = $docid->where('parent', $this->documentData['parent']);
                }
                $docid = $docid->first();
                if (!is_null($docid)) {
                    throw new ServiceActionException(\Lang::get('global.duplicate_alias_found'));
                }
            } // strip alias of special characters
            elseif ($this->documentData['alias']) {
                $this->documentData['alias'] = EvolutionCMS()->stripAlias($this->documentData['alias']);
                $docid = \EvolutionCMS\Models\SiteContent::query()->select('id')
                    ->withTrashed()
                    ->where('id', '<>', $this->documentData['id'])
                    ->where('alias', $this->documentData['alias'])->where('parent', $this->documentData['parent'])->first();
                if (!is_null($docid)) {
                    throw new ServiceActionException(\Lang::get('global.duplicate_alias_found'));
                }
            }
        } elseif ($this->documentData['alias']) {
            $this->documentData['alias'] = EvolutionCMS()->stripAlias($this->documentData['alias']);
        }
    }

    public function prepareCreateDocument()
    {
        $this->documentData['parent'] = (int)get_by_key($this->documentData, 'parent', 0, 'is_scalar');

        
        $this->documentData['menuindex'] = !empty($this->documentData['menuindex']) ? (int)$this->documentData['menuindex'] : 0;

        $this->documentData['createdby'] = EvolutionCMS()->getLoginUserID();
        $this->documentData['editedby'] = EvolutionCMS()->getLoginUserID();
        $this->documentData['createdon'] = $this->currentDate;
        $this->documentData['editedon'] = $this->currentDate;
        // invoke OnBeforeDocFormSave event
        switch (EvolutionCMS()->getConfig('docid_incrmnt_method')) {
            case '1':
                $id = \EvolutionCMS\Models\SiteContent::query()
                    ->withTrashed()
                    ->leftJoin('site_content as t1', 'site_content.id +1', '=', 't1.id')
                    ->whereNull('t1.id')->min('site_content.id');
                $id++;

                break;
            case '2':
                $id = \EvolutionCMS\Models\SiteContent::withTrashed()->max('id');
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
        $tmplvars = SiteTmplvarTemplate::query()->select('site_tmplvars.*')
            ->where('templateid', $this->documentData['template'])
            ->join('site_tmplvars', 'site_tmplvars.id', '=', 'site_tmplvar_templates.tmplvarid')->get();
        foreach ($tmplvars as $tmplvar) {
            if (isset($this->documentData[$tmplvar->name])) {
                $this->tvs[] = ['id' => $tmplvar->id, 'value' => $this->documentData[$tmplvar->name]];
            }
        }

    }

    public function saveTVs()
    {
        foreach ($this->tvs as $value) {
            \EvolutionCMS\Models\SiteTmplvarContentvalue::updateOrCreate(['contentid' => $this->documentData['id'], 'tmplvarid' => $value['id']], ['value' => $value['value']]);
        }

    }

    public function updateParent()
    {
        // update parent folder status
        if ($this->documentData['parent'] != 0) {
            $fields = array('isfolder' => 1);
            \EvolutionCMS\Models\SiteContent::withTrashed()->where('id', $this->documentData['parent'])->update(['isfolder' => 1]);
        }
    }

}
