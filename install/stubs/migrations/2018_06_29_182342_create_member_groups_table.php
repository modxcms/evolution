<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMemberGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('member_groups', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_group')->default(0);
			$table->integer('member')->default(0);
			$table->unique(['user_group','member'], 'ix_group_member');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('member_groups');
	}

}
