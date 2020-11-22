<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoleVars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_vars', function (Blueprint $table) {
            $table->integer('tmplvarid')->default(0);
            $table->integer('roleid')->default(0);
            $table->integer('rank')->default(0);
            $table->primary(['tmplvarid','roleid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_role_vars');
    }
}
