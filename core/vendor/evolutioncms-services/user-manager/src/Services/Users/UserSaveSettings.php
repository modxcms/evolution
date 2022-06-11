<?php namespace EvolutionCMS\UserManager\Services\Users;

use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\UserManager\Interfaces\UserServiceInterface;
use \EvolutionCMS\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class UserSaveSettings implements UserServiceInterface
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
        return [];
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

        // determine which settings can be saved blank (based on 'default_{settingname}' POST checkbox values)
        $defaults = array(
            'upload_images',
            'upload_media',
            'upload_flash',
            'upload_files'
        );

        // get user setting field names
        $customFields = $this->excludeStandardFields($this->userData);

        foreach ($customFields as $n => $v) {
            if (!in_array($n, $defaults) && (is_scalar($v) && trim($v) == '' || is_array($v) && empty($v))) {
                continue;
            } // ignore blacklist and empties
            $settings[$n] = $v; // this value should be saved
        }

        foreach ($defaults as $k) {
            if (isset($settings['default_' . $k]) && $settings['default_' . $k] == '1') {
                unset($settings[$k]);
            }
            unset($settings['default_' . $k]);
        }

        foreach ($settings as $n => $vl) {
            if (is_array($vl)) {
                $vl = implode(',', $vl);
            }
            if ((string)$vl != '') {
                \EvolutionCMS\Models\UserSetting::updateOrCreate(['setting_name' => $n, 'user' => $this->userData['id']],
                    ['setting_value' => $vl]);
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
