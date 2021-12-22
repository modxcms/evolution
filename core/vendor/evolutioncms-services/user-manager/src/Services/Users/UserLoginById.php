<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserLoginById extends UserLogin
{
    /**
     * @return \string[][]
     */
    public function getValidationRules(): array
    {
        return [
            'id' => ['required'],
            'context' => ['nullable', 'in:web,mgr'],
        ];
    }

    /**
     * @return array
     */
    public function getValidationMessages(): array
    {
        return ['id.required' => Lang::get("global.required_field", ['field' => 'username'])];
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

        if (isset($this->userData['context'])) {
            $this->context = $this->userData['context'];
        }

        $this->user = \EvolutionCMS\Models\User::query()->find($this->userData['id']);
        if (is_null($this->user)) {
            throw new ServiceActionException(\Lang::get('global.login_processor_unknown_user'));
        }
        if ($this->events) {
            // invoke OnBeforeManagerLogin event
            EvolutionCMS()->invokeEvent('OnBeforeManagerLogin', array(
                'username' => $this->user->username,
            ));
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
            ));
        }

        return $this->user;
    }

    public function checkPassword()
    {
        return true;
    }
}
