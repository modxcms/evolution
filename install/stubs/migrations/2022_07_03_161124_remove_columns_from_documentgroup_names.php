<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromDocumentgroupNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentgroup_names', function (Blueprint $table) {
            $table->dropColumn(['private_memgroup', 'private_webgroup']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membergroup_access', function (Blueprint $table) {
            $table->integer('private_memgroup')->nullable()->default(0)->comment('determine whether the document group is private to manager users');
            $table->integer('private_webgroup')->nullable()->default(0)->comment('determines whether the document is private to web users');
        });
    }
}
