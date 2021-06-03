<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use EvolutionCMS\Models\User;
use EvolutionCMS\Models\UserValue;
use EvolutionCMS\Models\SiteTmplvar;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class UserSaveValues implements UserServiceInterface
{
    use ExcludeStandardFieldsTrait;

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
    public function __construct(array $userData, bool $events = true, bool $cache = true)
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
    public function process(): bool
    {
        if (!$this->checkRules()) {
            throw new ServiceActionException(\Lang::get('global.error_no_privileges'));
        }


        if (!$this->validate()) {
            $exception = new ServiceValidationException();
            $exception->setValidationErrors($this->validateErrors);
            throw $exception;
        }

        $id = $this->userData['id'];
        unset($this->userData['id']);

        $values = $this->excludeStandardFields($this->userData);

        $tmplvars = SiteTmplvar::select('site_tmplvars.*', 'user_values.id AS value_id', 'user_values.value')
            ->whereIn('name', array_keys($values))
            ->leftJoin('user_values', function($query) use ($id) {
                $query->on('user_values.userid', '=', \DB::raw($id));
                $query->on('user_values.tmplvarid', '=', 'site_tmplvars.id');
            })
            ->get();

        foreach ($tmplvars as $tmplvar) {
            $value = (string) $values[$tmplvar->name];

            if ($value != '') {
                if ($tmplvar->value_id) {
                    UserValue::where('id', $tmplvar->value_id)->update([
                        'value' => $value,
                    ]);
                } else {
                    UserValue::create([
                        'tmplvarid' => $tmplvar->id,
                        'userid'    => $id,
                        'value'     => $value,
                    ]);
                }
            } else if ($tmplvar->value_id) {
                UserValue::where('id', $tmplvar->value_id)->delete();
            }
        }

        if ($this->cache) {
            EvolutionCMS()->clearCache('full');
        }

        return true;
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