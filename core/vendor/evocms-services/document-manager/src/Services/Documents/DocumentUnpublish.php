<?php namespace EvolutionCMS\DocumentManager\Services\Documents;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class DocumentUnpublish extends DocumentCreate
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

        $document->published = 0;
        $document->publishedby = EvolutionCMS()->getLoginUserID();
        $document->save();
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
        return EvolutionCMS()->hasPermission('publish_document');
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
