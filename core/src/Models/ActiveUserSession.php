<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property string $sid
 * @property int $internalKey
 * @property int $lasthit
 * @property string $ip
 */
class ActiveUserSession extends Eloquent\Model
{
    protected $primaryKey = 'sid';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'internalKey' => 'int',
        'lasthit'     => 'int'
    ];

    protected $fillable = [
        'sid',
        'internalKey',
        'lasthit',
        'ip'
    ];
}
