<?php namespace EvolutionCMS\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class UserEdit implements ServiceInterface
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
        return [
            'id' => ['required'],
            'username' => [Rule::unique('users')->ignore($this->userData['id'])],
            'password' => ['min:6', 'confirmed'],
            'email' => [Rule::unique('user_attributes')->ignore($this->userData['id'], 'internalKey')],
        ];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
            'id.required' => Lang::get("global.required_field", ['field' => 'username']),
            'password.confirmed' => Lang::get("global.password_confirmed", ['field' => 'password']),
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
                "mode" => "upd",
                "user" => &$this->userData,
            ));
        }

        if (!$this->validate()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($this->validateErrors);
            throw $exception;
        }
        $user = User::find($this->userData['id']);
        if (isset($this->userData['username']) && $this->userData['username'] != '') {
            $user->username = $this->userData['username'];
            $user->save();
        }
        $this->userData['internalKey'] = $user->getKey();
        if (isset($this->userData['dob'])) {
            if (!is_numeric($this->userData['dob'])) $this->userData['dob'] = null;
        }
        foreach ($this->userData as $attribute => $value) {
            if (isset($user->attributes->{$attribute}) && $attribute != 'id' && $attribute != 'internalKey' && $attribute != 'role') {
                $user->attributes->{$attribute} = $value;
            }
        }
        $user->attributes->save();
        // invoke OnWebSaveUser event
        if ($this->events) {
            EvolutionCMS()->invokeEvent("OnUserFormSave", array(
                "mode" => "upd",
                "userid" => $user->getKey(),
                "username" => $user->username,
                "userpassword" => isset($this->userData['clearPassword']) ? $this->userData['clearPassword'] : '',
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
        return ($_SESSION['mgrInternalKey'] == $this->userData['id'] || EvolutionCMS()->hasPermission('save_user'));
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