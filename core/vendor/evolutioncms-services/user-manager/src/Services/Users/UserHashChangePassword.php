<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class UserHashChangePassword implements UserServiceInterface
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
     * @var string
     */
    private $context;

    /**
     * UserRegistration constructor.
     * @param array $userData
     * @param bool $events
     * @param bool $cache
     */
    public function __construct(array $userData, bool $events = true, bool $cache = true)
    {
        $this->context = evo()->getContext();
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
            'hash' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
            'hash.required' => Lang::get("global.required_field", ['field' => 'hash']),
            'password.required' => Lang::get("global.required_field", ['field' => 'password']),
            'password.confirmed' => Lang::get("global.password_confirmed", ['field' => 'password']),
            'password.min' => Lang::get("global.password_gen_length"),

        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @throws ServiceActionException
     * @throws ServiceValidationException
     */
    public function process(): string
    {
        if (!$this->checkRules()) {
            throw new ServiceActionException(\Lang::get('global.error_no_privileges'));
        }

        if (!$this->validate()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($this->validateErrors);
            throw $exception;
        }

        $user = \EvolutionCMS\Models\User::where('cachepwd', $this->userData['hash'])->first();
        if (is_null($user)) {
            throw new ServiceActionException(\Lang::get('global.could_not_find_user'));
        }
        $user->password = EvolutionCMS()->getPasswordHash()->HashPassword($this->userData['password']);
        $user->cachepwd = '';
        $user->save();

        // invoke OnManagerChangePassword event
        EvolutionCMS()->invokeEvent('OnUserChangePassword', array(
            'userid' => $this->userData['id'],
            'username' => $_SESSION[$this->context. 'Shortname'],
            'userpassword' => $this->userData['password']
        ));
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
