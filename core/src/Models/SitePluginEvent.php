<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $pluginid
 * @property int $evtid
 * @property int $priority
 */
class SitePluginEvent extends Eloquent\Model
{
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
