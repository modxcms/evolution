<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemEventnamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_eventnames', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50)->default('');
			$table->integer('service')->default(0)->comment('System Service number');
			$table->string('groupname', 20)->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_eventnames');
	}

}
