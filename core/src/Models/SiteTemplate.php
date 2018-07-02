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
 *
 * Virtual
 * @property string $name Alias for templatename
 *
 * BelongsTo
 * @property null|Category $categories
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

	public function getNameAttribute()
    {
        return $this->templatename;
    }

    public function setNameAttribute($val)
    {
        $this->templatename = $val;
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

    /**
     * @return Eloquent\Relations\BelongsToMany
     */
    public function tvs() : Eloquent\Relations\BelongsToMany
    {

        return $this->belongsToMany(
            SiteTmplvar::class,
            (new SiteTmplvarTemplate())->getTable(),
            'templateid',
            'tmplvarid'
        )->withPivot('rank')
            ->orderBy('pivot_rank', 'ASC');
    }
}
