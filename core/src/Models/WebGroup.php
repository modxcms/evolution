<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\WebGroup
 *
 * @property int $id
 * @property int $webgroup
 * @property int $webuser
 *
 * @mixin \Eloquent
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

    public function user()
    {
        return $this->belongsTo(WebUser::class, 'member','id');
    }
}
