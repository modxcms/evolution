<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSitePluginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_plugins', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50)->default('');
			$table->string('description')->default('Plugin');
			$table->integer('editor_type')->default(0)->comment('0-plain text,1-rich text,2-code editor');
			$table->integer('category')->default(0)->comment('category id');
			$table->boolean('cache_type')->default(0)->comment('Cache option');
			$table->mediumText('plugincode')->nullable();
			$table->boolean('locked')->default(0);
			$table->text('properties', 65535)->nullable()->comment('Default Properties');
			$table->boolean('disabled')->default(0)->comment('Disables the plugin');
			$table->string('moduleguid', 32)->default('')->nullable()->comment('GUID of module from which to import shared parameters');
			$table->integer('createdon')->default(0);
			$table->integer('editedon')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_plugins');
	}

}
