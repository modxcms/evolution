<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;
use EvolutionCMS\Traits;
use Rocky\Eloquent\HasDynamicRelation;

/**
 * EvolutionCMS\Models\WebUser
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $cachepwd
 *
 * @mixin \Eloquent
 */
class User extends Eloquent\Model
{
    use Traits\Models\ManagerActions,
        HasDynamicRelation;

	public $timestamps = false;

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'password',
		'cachepwd'
	];

    public function attributes()
    {
        return $this->hasOne(UserAttribute::class,'internalKey','id');
    }

    public function memberGroups()
    {
        return $this->hasMany(MemberGroup::class,'member','id');
    }

    public function settings()
    {
        return $this->hasMany(UserSetting::class,'user','id');
    }

    public function delete()
    {
        $this->memberGroups()->delete();
        $this->attributes()->delete();
        $this->settings()->delete();

        return parent::delete();
    }
}
