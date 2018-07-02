<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;
use EvolutionCMS\Traits;

/**
 * @property string $setting_name
 * @property string $setting_value
 */
class SystemSetting extends Eloquent\Model
{
    use Traits\Models\ManagerActions;

	protected $primaryKey = 'setting_name';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'setting_value'
	];
}
