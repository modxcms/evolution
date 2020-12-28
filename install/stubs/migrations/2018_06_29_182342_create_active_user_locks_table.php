<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActiveUserLocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('active_user_locks', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('sid', 32)->default('');
			$table->integer('internalKey')->default(0);
			$table->integer('elementType')->default(0);
			$table->integer('elementId')->default(0);
			$table->integer('lasthit')->default(0);
			$table->unique(['elementType','elementId','sid'], 'ix_element_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('active_user_locks');
	}

}
