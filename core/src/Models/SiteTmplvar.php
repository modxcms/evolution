<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $caption
 * @property string $description
 * @property int $editor_type
 * @property int $category
 * @property int $locked
 * @property string $elements
 * @property int $rank
 * @property string $display
 * @property string $display_params
 * @property string $default_text
 * @property int $createdon
 * @property int $editedon
 */
class SiteTmplvar extends Eloquent\Model
{
	const CREATED_AT = 'createdon';
	const UPDATED_AT = 'editedon';
    protected $dateFormat = 'U';

	protected $casts = [
		'editor_type' => 'int',
		'category' => 'int',
		'locked' => 'int',
		'rank' => 'int',
		'createdon' => 'int',
		'editedon' => 'int'
	];

	protected $fillable = [
		'type',
		'name',
		'caption',
		'description',
		'editor_type',
		'category',
		'locked',
		'elements',
		'rank',
		'display',
		'display_params',
		'default_text'
	];
}
