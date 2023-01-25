<?php namespace EvolutionCMS\DocumentManager\Services\Documents;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentDelete extends DocumentCreate
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

        $document = SiteContent::query()->withTrashed()->find($this->documentData['id']);

        $children = $document->getAllChildren($document);
        $documentDeleteIds = $children;
        array_unshift($documentDeleteIds, $this->documentData['id']);

        foreach ($documentDeleteIds as $deleteId) {
            if (EvolutionCMS()->getConfig('site_start') == $deleteId) {
                throw new ServiceActionException("Document is 'Site start' and cannot be deleted!");
            }

            if (EvolutionCMS()->getConfig('site_unavailable_page') == $deleteId) {
                throw new ServiceActionException("Document is used as the 'Site unavailable page' and cannot be deleted!");
            }

            if (EvolutionCMS()->getConfig('error_page') == $deleteId) {
                throw new ServiceActionException("Document is used as the 'Site error page' and cannot be deleted!");
            }

            if (EvolutionCMS()->getConfig('unauthorized_page') == $deleteId) {
                throw new ServiceActionException("Document is used as the 'Site unauthorized page' and cannot be deleted!");
            }
        }
        SiteContent::query()
            ->withTrashed()
            ->whereIn('id', $documentDeleteIds)
            ->update(['deleted' => 1,
                'deletedby' => EvolutionCMS()->getLoginUserID(),
                'deletedon' => time()]);


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


}
