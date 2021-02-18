<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserRefreshToken implements UserServiceInterface
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
     * UserRefreshToken constructor.
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
        return ['refresh_token' => ['required', 'exists:users']];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return ['refresh_token.required' => Lang::get("global.required_field", ['field' => 'refresh_token'])];
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


        $user = \EvolutionCMS\Models\User::query()
            ->where('refresh_token', $this->userData['refresh_token'])->first();
        if (is_null($user)) {
            throw new ServiceActionException(\Lang::get('global.could_not_find_user'));
        }

        $user->access_token = hash('sha256', Str::random(32));
        $user->valid_to = Carbon::now()->addHours(11);
        $user->save();

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
