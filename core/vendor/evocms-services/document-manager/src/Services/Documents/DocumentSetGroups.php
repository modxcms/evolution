<?php namespace EvolutionCMS\DocumentManager\Services\Documents;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentSetGroups extends DocumentCreate
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


        $new_groups = array();
        // process the new input

        foreach ($this->documentData['document_groups'] as $value_pair) {
            list($group, $link_id) = explode(',', $value_pair); // @see actions/mutate_content.dynamic.php @ line 1138 (permissions list)
            $new_groups[$group] = $link_id;
        }

        // grab the current set of permissions on this document the user can access
        $isManager = EvolutionCMS()->hasPermission('access_permissions');
        $isWeb = EvolutionCMS()->hasPermission('web_access_permissions');
        $documentGroups = \EvolutionCMS\Models\DocumentGroup::query()->select('document_groups.id', 'document_groups.document_group')
            ->leftJoin('documentgroup_names', 'document_groups.document_group', '=', 'documentgroup_names.id')
            ->where(function ($query) use ($isWeb, $isManager) {
                $query->where(function ($query) use ($isManager) {
                    $query->whereRaw('1 = ' . (int)$isManager)
                        ->where('documentgroup_names.private_memgroup', true);
                })
                    ->orWhere(function ($query) use ($isWeb) {
                        $query->whereRaw('1 = ' . (int)$isWeb)
                            ->where('documentgroup_names.private_webgroup', true);
                    });
            })->where('document_groups.document', $this->documentData['id'])->get();

        $old_groups = array();
        foreach ($documentGroups as $documentGroup)
            $old_groups[$documentGroup->document_group] = $documentGroup->id;

        // update the permissions in the database
        $insertions = $deletions = array();
        foreach ($new_groups as $group => $link_id) {
            if (array_key_exists($group, $old_groups)) {
                unset($old_groups[$group]);
                continue;
            } elseif ($link_id == 'new') {
                $insertions[] = ['document_group' => (int)$group, 'document' => $this->documentData['id']];
            }
        }
        if (!empty($insertions)) {
            \EvolutionCMS\Models\DocumentGroup::query()->insert($insertions);
        }
        if (!empty($old_groups)) {
            \EvolutionCMS\Models\DocumentGroup::query()->whereIn('id', $old_groups)->delete();
        }
        // necessary to remove all permissions as document is public
        if ((isset($_POST['chkalldocs']) && $_POST['chkalldocs'] == 'on')) {
            \EvolutionCMS\Models\DocumentGroup::query()->where('document', $this->documentData['id'])->delete();
        }

        if ($this->cache) {
            EvolutionCMS()->clearCache('full');
        }

        return SiteContent::query()->withTrashed()->find($this->documentData['id']);
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


}
