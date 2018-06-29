<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $user
 * @property string $setting_name
 * @property string $setting_value
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
}
