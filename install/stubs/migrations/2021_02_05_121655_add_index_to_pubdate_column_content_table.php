<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToPubdateColumnContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_content', function (Blueprint $table) {
            $table->index(['pub_date', 'unpub_date', 'published'], 'pub_unpub_published');
            $table->index(['pub_date', 'unpub_date'], 'pub_unpub');
            $table->index(['unpub_date'], 'unpub');
            $table->index(['pub_date'], 'pub');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_content', function (Blueprint $table) {
            $table->dropIndex(['pub_unpub_published', 'pub_unpub', 'unpub', 'pub']);
        });
    }
}
