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
			$table->mediumText('value')->nullable();
			$table->unique(['tmplvarid','contentid'], 'ix_tvid_contentid');
        });
        $prefix = DB::getTablePrefix();
        $site_content_tmplvar = (new \EvolutionCMS\Models\SiteTmplvarContentvalue())->getTable();
        if(isset($_POST['database_type']) && $_POST['database_type'] != 'pgsql')
        DB::statement('ALTER TABLE '.$prefix.$site_content_tmplvar.' ADD FULLTEXT content_ft_idx(value)');
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
