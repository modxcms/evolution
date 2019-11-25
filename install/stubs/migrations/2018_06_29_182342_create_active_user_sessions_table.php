<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActiveUserSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('active_user_sessions', function(Blueprint $table)
		{
			$table->string('sid', 32)->default('')->primary();
			$table->integer('internalKey')->default(0);
			$table->integer('lasthit')->default(0);
			$table->string('ip', 50)->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('active_user_sessions');
	}

}
