<?php namespace EvolutionCMS\Services;

use EvolutionCMS\Exceptions\ServiceValidateException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use \EvolutionCMS\Models\User;

class UserManager implements ServiceInterface
{
    public $validate = [
        'username' => ['required', 'unique:users'],
        'email' => ['required', 'unique:user_attributes'],
    ];

    /**
     * @var array
     */
    public $messages = [
        'required' => 'Поле обязательно',
        'username.min' => 'Имя не меньше 5 символов',
        'username.unique' => 'Имя пользователя должно быть уникальным',
        'email.unique' => 'Email должен быть уникальным',
    ];

    public function get($id)
    {
        return User::find($id);
    }

    public function create(array $userData): User
    {
        $validator = \Validator::make($userData, $this->validate, $this->messages);

        if ($validator->fails()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($validator->messages()->toArray());
            throw $exception;
        }

    }

    public function edit($id, $userData)
    {

    }

    public function delete($id)
    {

    }

    public function dropPassword($userData)
    {

    }

    public function changePassword($userData)
    {

    }

    public function setRole($userData)
    {

    }

    public function setGroup($userData)
    {

    }

    public function login($userData)
    {

    }

    public function logout($userData)
    {

    }

}
