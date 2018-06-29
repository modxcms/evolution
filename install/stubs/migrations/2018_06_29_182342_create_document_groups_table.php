<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('document_groups', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('document_group')->default(0)->index('document_group');
			$table->integer('document')->default(0)->index('document');
			$table->unique(['document_group','document'], 'ix_dg_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('document_groups');
	}

}
