<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_roles', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50)->default('');
			$table->string('description')->default('');
			$table->integer('frames')->default(0);
			$table->integer('home')->default(0);
			$table->integer('view_document')->default(0);
			$table->integer('new_document')->default(0);
			$table->integer('save_document')->default(0);
			$table->integer('publish_document')->default(0);
			$table->integer('delete_document')->default(0);
			$table->integer('empty_trash')->default(0);
			$table->integer('action_ok')->default(0);
			$table->integer('logout')->default(0);
			$table->integer('help')->default(0);
			$table->integer('messages')->default(0);
			$table->integer('new_user')->default(0);
			$table->integer('edit_user')->default(0);
			$table->integer('logs')->default(0);
			$table->integer('edit_parser')->default(0);
			$table->integer('save_parser')->default(0);
			$table->integer('edit_template')->default(0);
			$table->integer('settings')->default(0);
			$table->integer('credits')->default(0);
			$table->integer('new_template')->default(0);
			$table->integer('save_template')->default(0);
			$table->integer('delete_template')->default(0);
			$table->integer('edit_snippet')->default(0);
			$table->integer('new_snippet')->default(0);
			$table->integer('save_snippet')->default(0);
			$table->integer('delete_snippet')->default(0);
			$table->integer('edit_chunk')->default(0);
			$table->integer('new_chunk')->default(0);
			$table->integer('save_chunk')->default(0);
			$table->integer('delete_chunk')->default(0);
			$table->integer('empty_cache')->default(0);
			$table->integer('edit_document')->default(0);
			$table->integer('change_password')->default(0);
			$table->integer('error_dialog')->default(0);
			$table->integer('about')->default(0);
			$table->integer('category_manager')->default(0);
			$table->integer('file_manager')->default(0);
			$table->integer('assets_files')->default(0);
			$table->integer('assets_images')->default(0);
			$table->integer('save_user')->default(0);
			$table->integer('delete_user')->default(0);
			$table->integer('save_password')->default(0);
			$table->integer('edit_role')->default(0);
			$table->integer('save_role')->default(0);
			$table->integer('delete_role')->default(0);
			$table->integer('new_role')->default(0);
			$table->integer('access_permissions')->default(0);
			$table->integer('bk_manager')->default(0);
			$table->integer('new_plugin')->default(0);
			$table->integer('edit_plugin')->default(0);
			$table->integer('save_plugin')->default(0);
			$table->integer('delete_plugin')->default(0);
			$table->integer('new_module')->default(0);
			$table->integer('edit_module')->default(0);
			$table->integer('save_module')->default(0);
			$table->integer('delete_module')->default(0);
			$table->integer('exec_module')->default(0);
			$table->integer('view_eventlog')->default(0);
			$table->integer('delete_eventlog')->default(0);
			$table->integer('new_web_user')->default(0);
			$table->integer('edit_web_user')->default(0);
			$table->integer('save_web_user')->default(0);
			$table->integer('delete_web_user')->default(0);
			$table->integer('web_access_permissions')->default(0);
			$table->integer('view_unpublished')->default(0);
			$table->integer('import_static')->default(0);
			$table->integer('export_static')->default(0);
			$table->integer('remove_locks')->default(0);
			$table->integer('display_locks')->default(0);
			$table->integer('change_resourcetype')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_roles');
	}

}
