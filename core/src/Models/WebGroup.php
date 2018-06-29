<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $webgroup
 * @property int $webuser
 */
class WebGroup extends Eloquent\Model
{
	public $timestamps = false;

	protected $casts = [
		'webgroup' => 'int',
		'webuser' => 'int'
	];

	protected $fillable = [
		'webgroup',
		'webuser'
	];
}
