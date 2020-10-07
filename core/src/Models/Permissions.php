<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\Permissions
 *
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string $lang_key
 * @property int $group_id
 * @property int $createdon
 * @property int $editedon
 *
 * Virtual
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 *
 * @mixin \Eloquent
 */
class Permissions extends Eloquent\Model
{

	protected $fillable = [
		'name',
		'key',
		'lang_key',
		'group_id',
	];


    public function attributes()
    {
        return $this->hasOne(PermissionsGroups::class,'id','group_id');
    }

}
