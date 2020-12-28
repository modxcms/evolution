<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebUserSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('web_user_settings', function(Blueprint $table)
		{
			$table->integer('webuser')->index('webuserid');
			$table->string('setting_name', 50)->default('')->index();
			$table->text('setting_value', 65535)->nullable();
			$table->primary(['webuser','setting_name']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('web_user_settings');
	}

}
