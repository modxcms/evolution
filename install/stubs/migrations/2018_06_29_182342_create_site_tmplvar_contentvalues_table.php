<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteTmplvarContentvaluesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_tmplvar_contentvalues', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('tmplvarid')->default(0)->index('idx_tmplvarid')->comment('Template Variable id');
			$table->integer('contentid')->default(0)->index('idx_id')->comment('Site Content Id');
			$table->text('value', 16777215)->nullable()->index('value_ft_idx');
			$table->unique(['tmplvarid','contentid'], 'ix_tvid_contentid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('site_tmplvar_contentvalues');
	}

}
