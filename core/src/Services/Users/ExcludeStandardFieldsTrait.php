<?php

namespace EvolutionCMS\Services\Users;

trait ExcludeStandardFieldsTrait
{
    protected function excludeStandardFields($userData)
    {
        $result = [];

        $ignore = array(
            'a',
            'id',
            'oldusername',
            'oldemail',
            'newusername',
            'fullname',
            'first_name',
            'middle_name',
            'last_name',
            'verified',
            'newpassword',
            'newpasswordcheck',
            'passwordgenmethod',
            'passwordnotifymethod',
            'specifiedpassword',
            'confirmpassword',
            'email',
            'phone',
            'mobilephone',
            'fax',
            'dob',
            'country',
            'street',
            'city',
            'state',
            'zip',
            'gender',
            'photo',
            'comment',
            'role',
            'failedlogincount',
            'blocked',
            'blockeduntil',
            'blockedafter',
            'user_groups',
            'mode',
            'blockedmode',
            'stay',
            'save',
            'theme_refresher'
        );

        foreach ($userData as $key => $value) {
            if (!isset($ignore[$key])) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
