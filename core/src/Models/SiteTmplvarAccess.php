<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $tmplvarid
 * @property int $documentgroup
 */
class SiteTmplvarAccess extends Eloquent\Model
{
	protected $table = 'site_tmplvar_access';
	public $timestamps = false;

	protected $casts = [
		'tmplvarid' => 'int',
		'documentgroup' => 'int'
	];

	protected $fillable = [
		'tmplvarid',
		'documentgroup'
	];
}
