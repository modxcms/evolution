<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $name
 * @property int $service
 * @property string $groupname
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
