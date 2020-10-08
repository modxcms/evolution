<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveColumnFromRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropColumn('frames');
            $table->dropColumn('home');
            $table->dropColumn('view_document');
            $table->dropColumn('new_document');
            $table->dropColumn('save_document');
            $table->dropColumn('publish_document');
            $table->dropColumn('delete_document');
            $table->dropColumn('empty_trash');
            $table->dropColumn('action_ok');
            $table->dropColumn('logout');
            $table->dropColumn('help');
            $table->dropColumn('messages');
            $table->dropColumn('new_user');
            $table->dropColumn('edit_user');
            $table->dropColumn('logs');
            $table->dropColumn('edit_parser');
            $table->dropColumn('save_parser');
            $table->dropColumn('edit_template');
            $table->dropColumn('settings');
            $table->dropColumn('credits');
            $table->dropColumn('new_template');
            $table->dropColumn('save_template');
            $table->dropColumn('delete_template');
            $table->dropColumn('edit_snippet');
            $table->dropColumn('new_snippet');
            $table->dropColumn('save_snippet');
            $table->dropColumn('delete_snippet');
            $table->dropColumn('edit_chunk');
            $table->dropColumn('new_chunk');
            $table->dropColumn('save_chunk');
            $table->dropColumn('delete_chunk');
            $table->dropColumn('empty_cache');
            $table->dropColumn('edit_document');
            $table->dropColumn('change_password');
            $table->dropColumn('error_dialog');
            $table->dropColumn('about');
            $table->dropColumn('category_manager');
            $table->dropColumn('file_manager');
            $table->dropColumn('assets_files');
            $table->dropColumn('assets_images');
            $table->dropColumn('save_user');
            $table->dropColumn('delete_user');
            $table->dropColumn('save_password');
            $table->dropColumn('edit_role');
            $table->dropColumn('save_role');
            $table->dropColumn('delete_role');
            $table->dropColumn('new_role');
            $table->dropColumn('access_permissions');
            $table->dropColumn('bk_manager');
            $table->dropColumn('new_plugin');
            $table->dropColumn('edit_plugin');
            $table->dropColumn('save_plugin');
            $table->dropColumn('delete_plugin');
            $table->dropColumn('new_module');
            $table->dropColumn('edit_module');
            $table->dropColumn('save_module');
            $table->dropColumn('delete_module');
            $table->dropColumn('exec_module');
            $table->dropColumn('view_eventlog');
            $table->dropColumn('delete_eventlog');
            $table->dropColumn('new_web_user');
            $table->dropColumn('edit_web_user');
            $table->dropColumn('save_web_user');
            $table->dropColumn('delete_web_user');
            $table->dropColumn('web_access_permissions');
            $table->dropColumn('view_unpublished');
            $table->dropColumn('import_static');
            $table->dropColumn('export_static');
            $table->dropColumn('remove_locks');
            $table->dropColumn('display_locks');
            $table->dropColumn('change_resourcetype');
        });

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('user_roles', function (Blueprint $table) {
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

}
