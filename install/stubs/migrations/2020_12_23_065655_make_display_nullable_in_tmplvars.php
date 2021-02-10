<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDisplayNullableInTmplvars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_tmplvars', function (Blueprint $table) {
            $table->string('display', 32)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_tmplvars', function (Blueprint $table) {
            $table->string('display', 32)->change();
        });
    }
}
