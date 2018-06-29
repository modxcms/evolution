<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $internalKey
 * @property string $fullname
 * @property int $role
 * @property string $email
 * @property string $phone
 * @property string $mobilephone
 * @property int $blocked
 * @property int $blockeduntil
 * @property int $blockedafter
 * @property int $logincount
 * @property int $lastlogin
 * @property int $thislogin
 * @property int $failedlogincount
 * @property string $sessionid
 * @property int $dob
 * @property int $gender
 * @property string $country
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $fax
 * @property string $photo
 * @property string $comment
 * @property int $createdon
 * @property int $editedon
 */
class WebUserAttribute extends Eloquent\Model
{
	const CREATED_AT = 'createdon';
	const UPDATED_AT = 'editedon';
    protected $dateFormat = 'U';

	protected $casts = [
		'internalKey' => 'int',
		'role' => 'int',
		'blocked' => 'int',
		'blockeduntil' => 'int',
		'blockedafter' => 'int',
		'logincount' => 'int',
		'lastlogin' => 'int',
		'thislogin' => 'int',
		'failedlogincount' => 'int',
		'dob' => 'int',
		'gender' => 'int',
		'createdon' => 'int',
		'editedon' => 'int'
	];

	protected $fillable = [
		'internalKey',
		'fullname',
		'role',
		'email',
		'phone',
		'mobilephone',
		'blocked',
		'blockeduntil',
		'blockedafter',
		'logincount',
		'lastlogin',
		'thislogin',
		'failedlogincount',
		'sessionid',
		'dob',
		'gender',
		'country',
		'street',
		'city',
		'state',
		'zip',
		'fax',
		'photo',
		'comment'
	];
}
