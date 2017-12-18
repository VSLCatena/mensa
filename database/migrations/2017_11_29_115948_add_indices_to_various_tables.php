<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndicesToVariousTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mensa_users', function (Blueprint $table) {
            $table->index('confirmed');
            $table->index('created_at');
            $table->index('deleted_at');
            $table->index('confirmation_code');
        });
        Schema::table('mensas', function (Blueprint $table) {
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mensa_users', function (Blueprint $table) {
            $table->dropIndex('confirmed');
            $table->dropIndex('created_at');
            $table->dropIndex('deleted_at');
            $table->dropIndex('confirmation_code');
        });
        Schema::table('mensas', function (Blueprint $table) {
            $table->dropIndex('date');
        });
    }
}
