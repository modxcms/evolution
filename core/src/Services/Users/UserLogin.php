<?php namespace EvolutionCMS\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserLogin implements ServiceInterface
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
     * @var int
     */
    private $blockedMinutes;
    /**
     * @var int
     */
    private $failedLoginAttempts;

    /**
     * @var
     */
    private $userSettings;

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
        $this->blockedMinutes = EvolutionCMS()->getConfig('blocked_minutes');
        $this->failedLoginAttempts = EvolutionCMS()->getConfig('failed_login_attempts');
        $this->userData = $userData;
        $this->events = $events;
        $this->cache = $cache;
    }

    /**
     * @return \string[][]
     */
    public function getValidationRules(): array
    {
        return ['username' => ['required'],
                'password' => ['required']];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return ['username.required' => Lang::get("global.required_field", ['field' => 'username']),
                'password.required' => Lang::get("global.required_field", ['field' => 'password'])];
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

        if ($this->events) {
            // invoke OnBeforeManagerLogin event
            EvolutionCMS()->invokeEvent('OnBeforeManagerLogin', array(
                'username' => $this->userData['username'],
                'userpassword' => $this->userData['password'],
                'rememberme' => $this->userData['rememberme']
            ));
        }

        $this->user = \EvolutionCMS\Models\User::query()
            ->where('username', $this->userData['username'])->first();
        if (is_null($this->user)) {
            throw new ServiceActionException(\Lang::get('global.login_processor_unknown_user'));
        }
        $this->userSettings = $this->user->settings->pluck('setting_value', 'setting_name')->toArray();

        $this->checkPassword();
        $this->validateAuth();
        $this->authProcess();
        $this->checkRemember();
        $this->clearActiveUsers();
        $this->writeLog();

        if ($this->events) {
            // invoke OnManagerLogin event
            EvolutionCMS()->invokeEvent('OnManagerLogin', array(
                'userid' => $this->user->getKey(),
                'username' => $this->user->username,
                'userpassword' => $this->userData['password'],
                'rememberme' => $this->userData['rememberme']
            ));
        }

        return $this->user;
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

    /**
     * @return bool
     */
    public function validateAuth(): bool
    {
        // blocked due to number of login errors, but get to try again
        if ($this->user->attributes->failedlogincount >= $this->failedLoginAttempts
            && $this->user->attributes->blockeduntil < time()) {
            $this->user->attributes->failedlogincount = 0;
            $this->user->attributes->blockeduntil = time() - 1;
            $this->user->attributes->save();
        }

        // this user has been blocked by an admin, so no way he's loggin in!
        if ($this->user->attributes->blocked == '1') {
            @session_destroy();
            session_unset();
            throw new ServiceActionException(\Lang::get('global.login_processor_blocked1'));
        }

        if ($this->user->attributes->verified != 1) {
            @session_destroy();
            session_unset();
            throw new ServiceActionException(\Lang::get('global.login_processor_verified'));
        }

        // blockuntil: this user has a block until date
        if ($this->user->attributes->blockeduntil > time()) {
            @session_destroy();
            session_unset();
            throw new ServiceActionException(\Lang::get('global.login_processor_blocked2'));
        }

        // blockafter: this user has a block after date
        if ($this->user->attributes->blockedafter > 0 && $this->user->attributes->blockedafter < time()) {
            @session_destroy();
            session_unset();
            throw new ServiceActionException(\Lang::get('global.login_processor_blocked2'));
        }

        // allowed ip
        if (isset($this->userSettings['allowed_ip'])) {
            if (($hostname = gethostbyaddr($_SERVER['REMOTE_ADDR'])) && ($hostname != $_SERVER['REMOTE_ADDR'])) {
                if (gethostbyname($hostname) != $_SERVER['REMOTE_ADDR']) {
                    throw new ServiceActionException(\Lang::get('global.login_processor_remotehost_ip'));
                }
            }
            if (!in_array($_SERVER['REMOTE_ADDR'], array_filter(array_map('trim', explode(',', $this->userSettings['allowed_ip']))))) {
                throw new ServiceActionException(\Lang::get('global.login_processor_remote_ip'));
            }
        }

        // allowed days
        if (isset($this->userSettings['allowed_days'])) {
            $date = getdate();
            $day = $date['wday'] + 1;
            if (!in_array($day, explode(',', $this->userSettings['allowed_days']))) {
                throw new ServiceActionException(\Lang::get('global.login_processor_date'));
            }
        }
        return true;
    }


    public function authProcess()
    {

        EvolutionCMS()->cleanupExpiredLocks();
        EvolutionCMS()->cleanupMultipleActiveUsers();

        $currentsessionid = session_regenerate_id();

        $_SESSION['usertype'] = 'manager'; // user is a backend user

        // get permissions
        $_SESSION['mgrShortname'] = $this->user->username;
        $_SESSION['mgrFullname'] = $this->user->attributes->fullname;
        $_SESSION['mgrEmail'] = $this->user->attributes->email;
        $_SESSION['mgrValidated'] = 1;
        $_SESSION['mgrInternalKey'] = $this->user->getKey();
        $_SESSION['mgrFailedlogins'] = $this->user->attributes->failedlogincount;
        $_SESSION['mgrLastlogin'] = $this->user->attributes->lastlogin;
        $_SESSION['mgrLogincount'] = $this->user->attributes->logincount; // login count
        $_SESSION['mgrRole'] = $this->user->attributes->role;
        $_SESSION['mgrPermissions'] = [];
        $mgrPermissions = \EvolutionCMS\Models\UserRole::find($this->user->attributes->role);
        if (!is_null($mgrPermissions)) {
            $permissionsRole = $mgrPermissions->toArray();
            $roleArray = \EvolutionCMS\Models\RolePermissions::query()->where('role_id', $this->user->attributes->role)->pluck('permission')->toArray();
            foreach ($roleArray as $role) {
                $permissionsRole[$role] = 1;
            }
            $_SESSION['mgrPermissions'] = $permissionsRole;
        }
        // successful login so reset fail count and update key values
        $this->user->attributes->failedlogincount = 0;
        $this->user->attributes->logincount += 1;
        $this->user->attributes->thislogin = time();
        $this->user->attributes->lastlogin = time();
        $this->user->attributes->sessionid = $currentsessionid;
        $this->user->attributes->save();

        $this->user->refresh_token = hash('sha256', Str::random(32));
        $this->user->access_token = hash('sha256', Str::random(32));
        $this->user->valid_to = Carbon::now()->addHours(11);
        $this->user->save();

        // get user's document groups
        $i = 0;

        $_SESSION['mgrDocgroups'] = \EvolutionCMS\Models\MemberGroup::query()
            ->join('membergroup_access', 'membergroup_access.membergroup', '=', 'member_groups.user_group')
            ->where('member_groups.member', $this->user->getKey())->pluck('documentgroup')->toArray();


        $_SESSION['mgrToken'] = md5($currentsessionid);

    }

    public function checkRemember()
    {

        if ($this->userData['rememberme'] == 1) {
            $_SESSION['modx.mgr.session.cookie.lifetime'] = (int)EvolutionCMS()->getConfig('session.cookie.lifetime');

            // Set a cookie separate from the session cookie with the username in it.
            // Are we using secure connection? If so, make sure the cookie is secure
            global $https_port;

            $secure = ((isset ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $_SERVER['SERVER_PORT'] == $https_port);
            if (version_compare(PHP_VERSION, '5.2', '<')) {
                setcookie('modx_remember_manager', $_SESSION['mgrShortname'], time() + 60 * 60 * 24 * 365, MODX_BASE_URL, '; HttpOnly', $secure);
            } else {
                setcookie('modx_remember_manager', $_SESSION['mgrShortname'], time() + 60 * 60 * 24 * 365, MODX_BASE_URL, NULL, $secure, true);
            }
        } else {
            $_SESSION['modx.mgr.session.cookie.lifetime'] = 0;

            // Remove the Remember Me cookie
            setcookie('modx_remember_manager', '', time() - 3600, MODX_BASE_URL);
        }
    }

    public function clearActiveUsers()
    {
        // Check if user already has an active session, if not check if user pressed logout end of last session
        $activeSession = \EvolutionCMS\Models\ActiveUserSession::where('internalKey', $this->user->getKey())->first();
        if (!is_null($activeSession)) {
            $lastHit = \EvolutionCMS\Models\ActiveUser::select('lasthit')->where('internalKey', $this->user->getKey())->where('action', '!=', 8)->first();
            if (!is_null($lastHit)) {
                $_SESSION['show_logout_reminder'] = array(
                    'type' => 'logout_reminder',
                    'lastHit' => $lastHit->lasthit
                );
            }
        }
    }

    public function writeLog()
    {
        $log = new \EvolutionCMS\Legacy\LogHandler();
        $log->initAndWriteLog('Logged in', EvolutionCMS()->getLoginUserID('mgr'), $_SESSION['mgrShortname'], '58', '-', 'EVO');
    }

    public function incrementFailedLoginCount(): void
    {
        $this->user->attributes->failedlogincount += 1;

        if ($this->user->attributes->failedlogincount >= $this->failedLoginAttempts) //block user for too many fail attempts
        {
            $this->user->attributes->blockeduntil = time() + ($this->blockedMinutes * 60);
        }
        $this->user->attributes->save();

        if ($this->user->attributes->failedlogincount < $this->failedLoginAttempts) {
            //sleep to help prevent brute force attacks
            $sleep = (int)$this->user->attributes->failedlogincount / 2;
            if ($sleep > 5) {
                $sleep = 5;
            }
            sleep($sleep);
        }
    }

    public function checkPassword()
    {
        if ($this->events) {
            // invoke OnManagerAuthentication event
            $rt = EvolutionCMS()->invokeEvent('OnManagerAuthentication', array(
                'userid' => $this->user->getKey(),
                'username' => $this->user->username,
                'userpassword' => $this->userData['password'],
                'savedpassword' => $this->user->password,
                'rememberme' => $this->userData['rememberme']
            ));
        }

        // check if plugin authenticated the user
        $matchPassword = false;
        if (!isset($rt) || !$rt || (is_array($rt) && !in_array(true, $rt))) {
            // check user password - local authentication
            $hashType = EvolutionCMS()->getManagerApi()->getHashType($this->user->password);

            if ($hashType == 'phpass') {
                $matchPassword = login($this->user->username, $this->userData['password'], $this->user->password);
            } elseif ($hashType == 'md5') {
                $matchPassword = loginMD5($this->user->getKey(), $this->userData['password'], $this->user->password, $this->user->username);
            } elseif ($hashType == 'v1') {
                $matchPassword = loginV1($this->user->getKey(), $this->userData['password'], $this->user->password, $this->user->username);
            } else {
                $matchPassword = false;
            }

        } else if ($rt === true || (is_array($rt) && in_array(true, $rt))) {
            $matchPassword = true;
        }

        if (!$matchPassword) {
            $this->incrementFailedLoginCount();
            throw new ServiceActionException(\Lang::get('global.login_processor_wrong_password'));
        }
    }

}
