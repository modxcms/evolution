<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteTmplvarAccessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_tmplvar_access', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('tmplvarid')->default(0);
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
		Schema::drop('site_tmplvar_access');
	}

}
