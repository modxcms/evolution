<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class UserRegistration implements UserServiceInterface
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
        $this->validate = $this->getValidationRules();
        $this->messages = $this->getValidationMessages();
        $this->userData = $userData;
        $this->events = $events;
        $this->cache = $cache;
    }

    /**
     * @return \string[][]
     */
    public function getValidationRules(): array
    {
        return [
            'username' => ['required', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'email' => ['required', 'unique:user_attributes'],
        ];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
            'username.required' => Lang::get("global.required_field", ['field' => 'username']),
            'password.required' => Lang::get("global.required_field", ['field' => 'password']),
            'password.confirmed' => Lang::get("global.password_confirmed", ['field' => 'password']),
            'email.required' => Lang::get("global.required_field", ['field' => 'email']),
            'password.min' => Lang::get("global.password_gen_length"),
            'username.unique' => Lang::get('global.username_unique'),
            'email.unique' => Lang::get('global.email_unique'),
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

        // invoke OnBeforeUserFormSave event
        if ($this->events) {
            EvolutionCMS()->invokeEvent("OnBeforeUserSave", array(
                "mode" => "new",
                "user" => &$this->userData,
            ));
        }

        if (!$this->validate()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($this->validateErrors);
            throw $exception;
        }


        $this->userData['clearPassword'] = $this->userData['password'];
        $this->userData['password'] = EvolutionCMS()->getPasswordHash()->HashPassword($this->userData['password']);
        if (isset($this->userData['dob'])) {
            if (!is_numeric($this->userData['dob'])) $this->userData['dob'] = null;
        }

        $user = User::create($this->userData);
        $this->userData['internalKey'] = $user->getKey();
        $user->attributes()->create($this->userData);

        // invoke OnWebSaveUser event
        if ($this->events) {
            EvolutionCMS()->invokeEvent("OnUserFormSave", array(
                "mode" => "new",
                "userid" => $user->getKey(),
                "username" => $user->username,
                "userpassword" => $this->userData['clearPassword'],
                "useremail" => $user->attributes->email,
                "userfullname" => $user->attributes->fullname
            ));
        }

        if ($this->cache) {
            EvolutionCMS()->clearCache('full');
        }

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
        $validator = \Validator::make($this->userData, $this->validate, $this->messages);
        $this->validateErrors = $validator->errors()->toArray();
        return !$validator->fails();
    }

}
