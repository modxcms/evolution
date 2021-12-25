<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use EvolutionCMS\Models\UserValue;
use Illuminate\Support\Facades\Lang;

class UserGetValues implements UserServiceInterface
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
    public function __construct(array $userData, bool $events = false, bool $cache = false)
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
        return ['id' => ['required']];
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
    public function process(): array
    {
        if (!$this->checkRules()) {
            throw new ServiceActionException(\Lang::get('global.error_no_privileges'));
        }


        if (!$this->validate()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($this->validateErrors);
            throw $exception;
        }

        $values = UserValue::query()
            ->select('site_tmplvars.name', 'user_values.value')
            ->join('site_tmplvars', 'site_tmplvars.id', '=', 'user_values.tmplvarid')
            ->where('userid', $this->userData['id'])
            ->when(!empty($this->userData['tvNames']), function($query) {
                $query->whereIn('site_tmplvars.name', $this->userData['tvNames']);
            })
            ->get()
            ->pluck('value', 'name')
            ->toArray();

        return $values;
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
        return true;
    }

}
