<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;

class UserLogout implements UserServiceInterface
{
    use SafelyDestroyUserSessionTrait;

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
    private $user;
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
            'context' => ['nullable', 'in:web,mgr'],
        ];
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

        if (isset($this->userData['context'])) {
            $this->context = $this->userData['context'];
        }

        $internalKey = EvolutionCMS()->getLoginUserID($this->context);
        if (!$internalKey) {
            return false;
        }
        $user = User::query()->find($internalKey);
        $username = '';
        if (!is_null($user)) {
            $user->refresh_token = '';
            $user->access_token = '';
            $user->valid_to = NULL;
            $user->save();
            $username = $_SESSION[$this->context . 'Shortname'];
            if(is_null($username)) $username = '';
            $sid = EvolutionCMS()->sid;
            if ($this->events) {
                // invoke OnBeforeManagerLogout event
                EvolutionCMS()->invokeEvent("OnBeforeManagerLogout",
                    array(
                        "userid" => $internalKey,
                        "username" => $username
                    ));
            }
        }

        $this->safelyDestroyUserSession();

        \EvolutionCMS\Models\ActiveUserLock::query()->where('sid', $sid)->delete();

        \EvolutionCMS\Models\ActiveUserSession::query()->where('sid', $sid)->delete();

        if ($this->events) {
            // invoke OnManagerLogout event
            EvolutionCMS()->invokeEvent("OnManagerLogout",
                array(
                    "userid" => $internalKey,
                    "username" => $username
                ));
        }
        return $username;
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
