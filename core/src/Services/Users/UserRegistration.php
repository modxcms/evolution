<?php namespace EvolutionCMS\Services\Users;

use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Interfaces\ServiceInterface;
use \EvolutionCMS\Models\User;

class UserRegistration implements ServiceInterface
{
    public $validate;

    public $messages;

    public $userData;

    public function __construct($userData)
    {
        $this->validate = $this->getValidationRules();
        $this->messages = $this->getValidationMessages();
        $this->userData = $userData;
    }

    public function getValidationRules(): array
    {
        return $validate = [
            'username' => ['required', 'unique:users'],
            'email' => ['required', 'unique:user_attributes'],
        ];
    }

    public function getValidationMessages(): array
    {
        return $messages = [
            'required' => 'Поле обязательно',
            'username.min' => 'Имя не меньше 5 символов',
            'username.unique' => 'Имя пользователя должно быть уникальным',
            'email.unique' => 'Email должен быть уникальным',
        ];
    }

    public function process()
    {
        try {
            $this->validation();
        } catch (ServiceValidationException $e) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($e->getValidationErrors());
            throw $exception;
        }
    }

    public function checkRules(): bool
    {

    }

    public function validation(): void
    {
        $validator = \Validator::make($this->userData, $this->validate, $this->messages);

        if ($validator->fails()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($validator->messages()->toArray());
            throw $exception;
        }
    }


}
