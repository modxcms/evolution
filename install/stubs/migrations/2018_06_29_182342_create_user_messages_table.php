<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_messages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('type', 15)->default('');
			$table->string('subject', 60)->default('');
			$table->text('message', 65535)->nullable();
			$table->integer('sender')->default(0);
			$table->integer('recipient')->default(0);
			$table->boolean('private')->default(0);
			$table->integer('postdate')->default(0);
			$table->boolean('messageread')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_messages');
	}

}
