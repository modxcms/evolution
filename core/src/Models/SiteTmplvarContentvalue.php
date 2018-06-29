<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $tmplvarid
 * @property int $contentid
 * @property string $value
 */
class SiteTmplvarContentvalue extends Eloquent\Model
{
	public $timestamps = false;

	protected $casts = [
		'tmplvarid' => 'int',
		'contentid' => 'int'
	];

	protected $fillable = [
		'tmplvarid',
		'contentid',
		'value'
	];
}
