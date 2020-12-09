<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tmplvarid')->default(0);
            $table->integer('userid')->default(0);
            $table->mediumText('value')->nullable();
            $table->index('tmplvarid');
            $table->index('userid');
            $table->unique(['tmplvarid','userid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_values');
    }
}
