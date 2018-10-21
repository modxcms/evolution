<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\WebgroupName
 *
 * @property int $id
 * @property string $name
 *
 * BelongsToMany
 * @property Eloquent\Collection $users
 * @property Eloquent\Collection $documentGroups
 *
 * @mixin \Eloquent
 */
class WebgroupName extends Eloquent\Model
{
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

    public function users(): Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(WebUser::class, 'web_groups', 'webgroup', 'webuser');
    }

    public function documentGroups(): Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(DocumentgroupName::class, 'webgroup_access', 'webgroup', 'documentgroup');
    }
}
