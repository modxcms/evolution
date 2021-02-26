<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;

class UserHashLogin extends UserLogin
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
     * @var User
     */
    public $user;

    /**
     * @var
     */
    private $userSettings;


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


        $this->user = \EvolutionCMS\Models\User::query()
            ->where('cachepwd', $this->userData['hash'])->first();
        if (is_null($this->user)) {
            throw new ServiceActionException(\Lang::get('global.login_processor_unknown_user'));
        }

        $this->userSettings = $this->user->settings->pluck('setting_value', 'setting_name')->toArray();

        $this->validateAuth();

        $this->authProcess();
        $this->checkRemember();
        $this->clearActiveUsers();

        if ($this->events) {
            // invoke OnManagerLogin event
            EvolutionCMS()->invokeEvent('OnManagerLogin', array(
                'userid' => $this->user->getKey(),
                'username' => $this->user->username,
                'userpassword' => $this->userData['password'],
                'rememberme' => $this->userData['rememberme']
            ));
        }
        $this->user->cachepwd = '';
        $this->user->save();
        return $this->user;
    }


}