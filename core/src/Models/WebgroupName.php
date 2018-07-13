<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\WebgroupName
 *
 * @property int $id
 * @property string $name
 *
 * @mixin \Eloquent
 */
class WebgroupName extends Eloquent\Model
{
	public $timestamps = false;

	protected $fillable = [
		'name'
	];
}
