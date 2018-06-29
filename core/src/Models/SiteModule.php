<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $editor_type
 * @property int $disabled
 * @property int $category
 * @property int $wrap
 * @property int $locked
 * @property string $icon
 * @property int $enable_resource
 * @property string $resourcefile
 * @property int $createdon
 * @property int $editedon
 * @property string $guid
 * @property int $enable_sharedparams
 * @property string $properties
 * @property string $modulecode
 */
class SiteModule extends Eloquent\Model
{
	const CREATED_AT = 'createdon';
	const UPDATED_AT = 'editedon';
    protected $dateFormat = 'U';

	protected $casts = [
		'editor_type' => 'int',
		'disabled' => 'int',
		'category' => 'int',
		'wrap' => 'int',
		'locked' => 'int',
		'enable_resource' => 'int',
		'createdon' => 'int',
		'editedon' => 'int',
		'enable_sharedparams' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'editor_type',
		'disabled',
		'category',
		'wrap',
		'locked',
		'icon',
		'enable_resource',
		'resourcefile',
		'guid',
		'enable_sharedparams',
		'properties',
		'modulecode'
	];
}
