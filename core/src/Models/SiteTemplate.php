<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $templatename
 * @property string $description
 * @property int $editor_type
 * @property int $category
 * @property string $icon
 * @property int $template_type
 * @property string $content
 * @property int $locked
 * @property int $selectable
 * @property int $createdon
 * @property int $editedon
 */
class SiteTemplate extends Eloquent\Model
{
	const CREATED_AT = 'createdon';
	const UPDATED_AT = 'editedon';
    protected $dateFormat = 'U';

	protected $casts = [
		'editor_type' => 'int',
		'category' => 'int',
		'template_type' => 'int',
		'locked' => 'int',
		'selectable' => 'int',
		'createdon' => 'int',
		'editedon' => 'int'
	];

	protected $fillable = [
		'templatename',
		'description',
		'editor_type',
		'category',
		'icon',
		'template_type',
		'content',
		'locked',
		'selectable'
	];
}
