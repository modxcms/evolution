<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $webuser
 * @property string $setting_name
 * @property string $setting_value
 */
class WebUserSetting extends Eloquent\Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'webuser' => 'int'
    ];

    protected $fillable = [
        'setting_value'
    ];
}
