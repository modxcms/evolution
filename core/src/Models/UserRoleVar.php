<?php

namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\UserRoleVar
 *
 * @property int $tmplvarid
 * @property int $roleid
 * @property int $rank
 *
 * @mixin \Eloquent
 */
class UserRoleVar extends Eloquent\Model
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tmplvarid' => 'int',
		'roleid' => 'int',
		'rank' => 'int'
	];

	protected $fillable = [
	    'tmplvarid',
        'roleid',
		'rank'
	];

    public function tmplvar()
    {
        return $this->belongsTo(SiteTmplvar::class, 'tmplvarid','id');
    }
}
