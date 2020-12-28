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
class UserSetting extends Eloquent\Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'user' => 'int'
    ];

    protected $fillable = [
        'user',
        'setting_name',
        'setting_value'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user','id');
    }
}
