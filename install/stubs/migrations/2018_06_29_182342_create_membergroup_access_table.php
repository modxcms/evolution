<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembergroupAccessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('membergroup_access', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('membergroup')->default(0);
			$table->integer('documentgroup')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('membergroup_access');
	}

}
