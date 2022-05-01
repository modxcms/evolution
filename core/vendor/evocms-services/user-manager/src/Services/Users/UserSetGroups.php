<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;

class UserSetGroups extends UserSetRole
{
    /**
     * @var array
     */
    public $userData;


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
        if (is_array($this->userData['groups'])) {
            $user->memberGroups()->delete();
            foreach ($this->userData['groups'] as $group) {
                $user->memberGroups()->create(['user_group' => $group]);

            }
        }
        return $user;
    }


}
