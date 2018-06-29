<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $category
 * @property int $rank
 */
class Category extends Eloquent\Model
{
	public $timestamps = false;

	protected $casts = [
		'rank' => 'int'
	];

	protected $fillable = [
		'category',
		'rank'
	];
}
