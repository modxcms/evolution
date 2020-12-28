<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActiveUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('active_users', function(Blueprint $table)
		{
			$table->string('sid', 32)->default('')->primary();
			$table->integer('internalKey')->default(0);
			$table->string('username', 50)->default('');
			$table->integer('lasthit')->default(0);
			$table->string('action', 10)->default('');
			$table->integer('id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('active_users');
	}

}
