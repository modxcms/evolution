<?php namespace EvolutionCMS\DocumentManager\Services\Documents;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentClearCart extends DocumentCreate
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
        ];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
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


        $ids = \EvolutionCMS\Models\SiteContent::query()->withTrashed()->where('deleted', 1)->pluck('id')->toArray();
        if ($this->events) {
            // invoke OnBeforeEmptyTrash event
            EvolutionCMS()->invokeEvent("OnBeforeEmptyTrash",
                array(
                    "ids" => $ids
                ));
        }
        // remove the document groups link.
        \EvolutionCMS\Models\DocumentGroup::query()->whereIn('document', $ids)->delete();

        // remove the TV content values.
        \EvolutionCMS\Models\SiteTmplvarContentvalue::query()->whereIn('contentid', $ids)->delete();

        //'undelete' the document.
        \EvolutionCMS\Models\SiteContent::query()->withTrashed()->where('deleted', 1)->forceDelete();

        // invoke OnEmptyTrash event
        if ($this->events) {
            EvolutionCMS()->invokeEvent("OnEmptyTrash",
                array(
                    "ids" => $ids
                ));
        }
        if ($this->cache) {
            EvolutionCMS()->clearCache('full');
        }
        return SiteContent::query()->withTrashed()->first();
    }

    /**
     * @return bool
     */
    public function checkRules(): bool
    {
        return EvolutionCMS()->hasPermission('delete_document');
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }


}
