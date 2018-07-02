<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;
use EvolutionCMS\Traits;

/**
 * @property int $pluginid
 * @property int $evtid
 * @property int $priority
 */
class SitePluginEvent extends Eloquent\Model
{
    use Traits\Models\ManagerActions;

	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'pluginid' => 'int',
		'evtid' => 'int',
		'priority' => 'int'
	];

	protected $fillable = [
		'priority'
	];
}
