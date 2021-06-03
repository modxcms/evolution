<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class UserClearSettings implements UserServiceInterface
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
    public $userData;

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
     * UserRegistration constructor.
     * @param array $userData
     * @param bool $events
     * @param bool $cache
     */
    public function __construct(array $userData, bool $events = true, bool $cache = true)
    {
        $this->userData = $userData;
        $this->events = $events;
        $this->cache = $cache;
        $this->validate = $this->getValidationRules();
        $this->messages = $this->getValidationMessages();
    }

    /**
     * @return \string[][]
     */
    public function getValidationRules(): array
    {
        return ['id' => ['required'],];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return ['id.required' => Lang::get("global.required_field", ['field' => 'id'])];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @throws ServiceActionException
     * @throws ServiceValidationException
     */
    public function process(): bool
    {
        if (!$this->checkRules()) {
            throw new ServiceActionException(\Lang::get('global.error_no_privileges'));
        }


        if (!$this->validate()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($this->validateErrors);
            throw $exception;
        }

        \EvolutionCMS\Models\UserSetting::where('user', $this->userData['id'])->delete();


        if ($this->cache) {
            EvolutionCMS()->clearCache('full');
        }

        return true;
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
        $validator = \Validator::make($this->userData, $this->validate, $this->messages);
        $this->validateErrors = $validator->errors()->toArray();
        return !$validator->fails();
    }

}