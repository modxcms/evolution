<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property int $id
 * @property string $type
 * @property string $subject
 * @property string $message
 * @property int $sender
 * @property int $recipient
 * @property int $private
 * @property int $postdate
 * @property bool $messageread
 */
class UserMessage extends Eloquent\Model
{
	public $timestamps = false;

	protected $casts = [
		'sender' => 'int',
		'recipient' => 'int',
		'private' => 'int',
		'postdate' => 'int',
		'messageread' => 'bool'
	];

	protected $fillable = [
		'type',
		'subject',
		'message',
		'sender',
		'recipient',
		'private',
		'postdate',
		'messageread'
	];
}
