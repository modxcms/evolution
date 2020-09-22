<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteHtmlsnippetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_htmlsnippets', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 100)->default('');
			$table->string('description')->default('Chunk');
			$table->integer('editor_type')->default(0)->comment('0-plain text,1-rich text,2-code editor');
			$table->string('editor_name', 50)->default('none');
			$table->integer('category')->default(0)->comment('category id');
			$table->boolean('cache_type')->default(0)->comment('Cache option');
			$table->mediumText('snippet')->nullable();
			$table->boolean('locked')->default(0);
			$table->integer('createdon')->default(0);
			$table->integer('editedon')->default(0);
			$table->boolean('disabled')->default(0)->comment('Disables the snippet');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_htmlsnippets');
	}

}
