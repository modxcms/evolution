<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;
use EvolutionCMS\Traits;

/**
 * EvolutionCMS\Models\Category
 *
 * @property int $id
 * @property string $category
 * @property int $rank
 *
 * HasMany
 * @property Eloquent\Collection $templates
 * @property Eloquent\Collection $chunks
 * @property Eloquent\Collection $snippets
 * @property Eloquent\Collection $plugins
 * @property Eloquent\Collection $modules
 * @property Eloquent\Collection $tvs
 *
 * Virtual
 * @property string $name Alias for templatename
 *
 * @mixin \Eloquent
 */
class Category extends Eloquent\Model
{
    use Traits\Models\ManagerActions;

	public $timestamps = false;

	protected $casts = [
		'rank' => 'int'
	];

	protected $fillable = [
		'category',
		'rank'
	];

    public function templates() : Eloquent\Relations\HasMany
    {
        return $this->hasMany(SiteTemplate::class, 'category', 'id');
    }

    public function chunks() : Eloquent\Relations\HasMany
    {
        return $this->hasMany(SiteHtmlsnippet::class, 'category', 'id');
    }

    public function snippets() : Eloquent\Relations\HasMany
    {
        return $this->hasMany(SiteSnippet::class, 'category', 'id');
    }

    public function plugins() : Eloquent\Relations\HasMany
    {
        return $this->hasMany(SitePlugin::class, 'category', 'id');
    }

    public function modules() : Eloquent\Relations\HasMany
    {
        return $this->hasMany(SiteModule::class, 'category', 'id');
    }

    public function tvs() : Eloquent\Relations\HasMany
    {
        return $this->hasMany(SiteTmplvar::class, 'category', 'id');
    }

    public function getNameAttribute()
    {
        return $this->category;
    }

    public function setNameAttribute($val)
    {
        $this->category = $val;
    }
}
