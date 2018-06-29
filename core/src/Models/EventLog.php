<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property int $eventid
 * @property int $createdon
 * @property int $type
 * @property int $user
 * @property int $usertype
 * @property string $source
 * @property string $description
 */
class EventLog extends Eloquent\Model
{
	protected $table = 'event_log';
	public $timestamps = false;

	protected $casts = [
		'eventid' => 'int',
		'createdon' => 'int',
		'type' => 'int',
		'user' => 'int',
		'usertype' => 'int'
	];

	protected $fillable = [
		'eventid',
		'type',
		'user',
		'usertype',
		'source',
		'description'
	];
}
