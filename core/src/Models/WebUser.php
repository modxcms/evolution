<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $cachepwd
 */
class WebUser extends Eloquent\Model
{
	public $timestamps = false;

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'password',
		'cachepwd'
	];
}
