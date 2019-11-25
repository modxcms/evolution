<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;
use EvolutionCMS\Traits;
use Rocky\Eloquent\HasDynamicRelation;

/**
 * EvolutionCMS\Models\ManagerUser
 *
 * @property int $id
 * @property string $username
 * @property string $password
 *
 * @mixin \Eloquent
 */
class ManagerUser extends Eloquent\Model
{
    use Traits\Models\ManagerActions,
        HasDynamicRelation;

	public $timestamps = false;

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'password'
	];

    public function attributes()
    {
        return $this->hasOne(UserAttribute::class,'internalKey','id');
    }

    public function memberGroups()
    {
        return $this->hasMany(MemberGroup::class,'member','id');
    }

    public function groups() : Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MembergroupName::class, 'member_groups', 'member', 'user_group');
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
