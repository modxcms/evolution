<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\MembergroupName
 *
 * @property int $id
 * @property string $name
 *
 * @mixin \Eloquent
 */
class MembergroupName extends Eloquent\Model
{
	public $timestamps = false;

	protected $fillable = [
		'name'
	];
}
