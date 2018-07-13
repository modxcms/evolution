<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;
use EvolutionCMS\Traits;

/**
 * EvolutionCMS\Models\UserMessage
 *
 * @property int $id
 * @property string $type
 * @property string $subject
 * @property string $message
 * @property int $sender
 * @property int $recipient
 * @property int $private
 * @property int $postdate
 * @property bool $messageread
 *
 * @mixin \Eloquent
 */
class UserMessage extends Eloquent\Model
{
    use Traits\Models\ManagerActions;

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
