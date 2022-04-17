<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContextColumnToMembergroupAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membergroup_access', function (Blueprint $table) {
            $table->addColumn('integer', 'context')->default(0);
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
            $table->dropColumn('context');
        });
    }
}
