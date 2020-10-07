<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\PermissionsGroups
 *
 * @property int $id
 * @property string $name
 * @property string $lang_key
 * @property int $createdon
 * @property int $editedon
 *
 * Virtual
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 *
 * @mixin \Eloquent
 */
class PermissionsGroups extends Eloquent\Model
{

	protected $fillable = [
		'name',
		'lang_key',
	];

    public function permissions()
    {
        return $this->hasMany(Permissions::class,'group_id','id');
    }
}
