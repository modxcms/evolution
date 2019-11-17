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
class WebUser extends Eloquent\Model
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
        return $this->hasOne(WebUserAttribute::class,'internalKey','id');
    }

    public function memberGroups()
    {
        return $this->hasMany(WebGroup::class,'webuser','id');
    }

    public function settings()
    {
        return $this->hasMany(WebUserSetting::class,'webuser','id');
    }

    public function delete()
    {
        $this->memberGroups()->delete();
        $this->attributes()->delete();
        $this->settings()->delete();

        return parent::delete();
    }
}
