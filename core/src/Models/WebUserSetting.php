<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Models\WebUserSetting
 *
 * @property int $webuser
 * @property string $setting_name
 * @property string $setting_value
 *
 * @mixin \Eloquent
 */
class WebUserSetting extends Eloquent\Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'webuser' => 'int'
    ];

    protected $fillable = [
        'setting_name',
        'setting_value'
    ];

    public function user()
    {
        return $this->belongsTo(WebUser::class, 'webuser','id');
    }
}
