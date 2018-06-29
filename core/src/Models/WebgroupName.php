<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $name
 */
class WebgroupName extends Eloquent\Model
{
	public $timestamps = false;

	protected $fillable = [
		'name'
	];
}
