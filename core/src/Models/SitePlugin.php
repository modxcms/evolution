<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $editor_type
 * @property int $category
 * @property bool $cache_type
 * @property string $plugincode
 * @property int $locked
 * @property string $properties
 * @property int $disabled
 * @property string $moduleguid
 * @property int $createdon
 * @property int $editedon
 *
 * BelongsTo
 * @property null|Category $categories
 */
class SitePlugin extends Eloquent\Model
{
	const CREATED_AT = 'createdon';
	const UPDATED_AT = 'editedon';
    protected $dateFormat = 'U';

	protected $casts = [
		'editor_type' => 'int',
		'category' => 'int',
		'cache_type' => 'bool',
		'locked' => 'int',
		'disabled' => 'int',
		'createdon' => 'int',
		'editedon' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'editor_type',
		'category',
		'cache_type',
		'plugincode',
		'locked',
		'properties',
		'disabled',
		'moduleguid'
	];

	public function scopeActivePhx(Eloquent\Builder $builder)
    {
        return $builder->where('disabled', '!=', 1)
            ->whereRaw('plugincode LIKE "%phx.parser.class.inc.php%OnParseDocument();%"');
    }

    public function categories() : Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    public function categoryName($default = '')
    {
        return $this->categories === null ? $default : $this->categories->category;
    }

    public function categoryId()
    {
        return $this->categories === null ? null : $this->categories->getKey();
    }
}
