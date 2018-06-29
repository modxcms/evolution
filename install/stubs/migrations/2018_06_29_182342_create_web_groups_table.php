<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('web_groups', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('webgroup')->default(0);
			$table->integer('webuser')->default(0);
			$table->unique(['webgroup','webuser'], 'ix_group_user');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('web_groups');
	}

}
