<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $webgroup
 * @property int $documentgroup
 */
class WebgroupAccess extends Eloquent\Model
{
	protected $table = 'webgroup_access';
	public $timestamps = false;

	protected $casts = [
		'webgroup' => 'int',
		'documentgroup' => 'int'
	];

	protected $fillable = [
		'webgroup',
		'documentgroup'
	];
}
