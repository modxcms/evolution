<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\UserSetting
 *
 * @property int $user
 * @property string $setting_name
 * @property string $setting_value
 *
 * @mixin \Eloquent
 */
class UserSetting extends Eloquent\Model
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user' => 'int'
	];

	protected $fillable = [
		'setting_value'
	];

    public function user()
    {
        return $this->belongsTo(ManagerUser::class, 'user','id');
    }
}
