<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;

class UserSetRole implements UserServiceInterface
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
    }

    /**
     * @return \string[][]
     */
    public function getValidationRules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [];
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

        $user = User::find($this->userData['id']);
        if (is_null($user)) {
            throw new ServiceActionException(\Lang::get('global.user_doesnt_exist'));
        }
        $user->attributes->role = $this->userData['role'];
        $user->attributes->save();

        return $user;
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
        return true;
    }

}
