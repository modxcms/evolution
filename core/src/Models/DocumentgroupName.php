<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\DocumentgroupName
 *
 * @property int $id
 * @property string $name

 *
 * BelongsToMany
 * @property Eloquent\Collection $documents
 *
 * @mixin \Eloquent
 */
class DocumentgroupName extends Eloquent\Model
{
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function documents(): Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SiteContent::class, 'document_groups', 'document_group', 'document')
            ->withTrashed();
    }
}
