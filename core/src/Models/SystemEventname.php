<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\SystemEventname
 *
 * @property int $id
 * @property string $name
 * @property int $service
 * @property string $groupname
 *
 * @mixin \Eloquent
 */
class SystemEventname extends Eloquent\Model
{
	public $timestamps = false;

	protected $casts = [
		'service' => 'int'
	];

	protected $fillable = [
		'name',
		'service',
		'groupname'
	];
}
