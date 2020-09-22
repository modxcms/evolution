<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteModulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_modules', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50)->default('');
			$table->string('description')->default('0');
			$table->integer('editor_type')->default(0)->comment('0-plain text,1-rich text,2-code editor');
			$table->boolean('disabled')->default(0);
			$table->integer('category')->default(0)->comment('category id');
			$table->boolean('wrap')->default(0);
			$table->boolean('locked')->default(0);
			$table->string('icon')->default('')->comment('url to module icon');
			$table->boolean('enable_resource')->default(0)->comment('enables the resource file feature');
			$table->string('resourcefile')->default('')->comment('a physical link to a resource file');
			$table->integer('createdon')->default(0);
			$table->integer('editedon')->default(0);
			$table->string('guid', 32)->default('')->comment('globally unique identifier');
			$table->boolean('enable_sharedparams')->default(0);
			$table->text('properties', 65535)->nullable();
			$table->mediumText('modulecode')->nullable()->comment('module boot up code');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_modules');
	}

}
