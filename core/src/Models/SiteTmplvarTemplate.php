<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $tmplvarid
 * @property int $templateid
 * @property int $rank
 */
class SiteTmplvarTemplate extends Eloquent\Model
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tmplvarid' => 'int',
		'templateid' => 'int',
		'rank' => 'int'
	];

	protected $fillable = [
		'rank'
	];
}
