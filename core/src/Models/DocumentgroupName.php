<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\DocumentgroupName
 *
 * @property int $id
 * @property string $name
 * @property int $private_memgroup
 * @property int $private_webgroup
 *
 * BelongsToMany
 * @property Eloquent\Collection $documents
 * @property Eloquent\Collection $memberGroups
 * @property Eloquent\Collection $webGroups
 *
 * @mixin \Eloquent
 */
class DocumentgroupName extends Eloquent\Model
{
    public $timestamps = false;

    protected $casts = [
        'private_memgroup' => 'int',
        'private_webgroup' => 'int'
    ];

    protected $fillable = [
        'name',
        'private_memgroup',
        'private_webgroup'
    ];

    public function documents(): Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SiteContent::class, 'document_groups', 'document_group', 'document')
            ->withTrashed();
    }

    public function memberGroups(): Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MembergroupName::class, 'membergroup_access', 'documentgroup', 'membergroup');
    }

    public function webGroups(): Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(WebgroupName::class, 'webgroup_access', 'documentgroup', 'webgroup');
    }
}
