<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteTmplvarTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_tmplvar_templates', function(Blueprint $table)
		{
			$table->integer('tmplvarid')->default(0)->comment('Template Variable id');
			$table->integer('templateid')->default(0);
			$table->integer('rank')->default(0);
			$table->primary(['tmplvarid','templateid']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_tmplvar_templates');
	}

}
