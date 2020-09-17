<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWebUserAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_user_attributes', function (Blueprint $table) {
            $table->string('middle_name')->after('fullname')->nullable();
            $table->string('last_name')->after('fullname')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_user_attributes', function (Blueprint $table) {
            $table->dropColumn('middle_name');
            $table->dropColumn('last_name');
        });
    }
}
