<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\MembergroupAccess
 *
 * @property int $id
 * @property int $membergroup
 * @property int $documentgroup
 *
 * @mixin \Eloquent
 */
class MembergroupAccess extends Eloquent\Model
{
	protected $table = 'membergroup_access';
	public $timestamps = false;

	protected $casts = [
		'membergroup' => 'int',
		'documentgroup' => 'int'
	];

	protected $fillable = [
		'membergroup',
		'documentgroup'
	];
}
