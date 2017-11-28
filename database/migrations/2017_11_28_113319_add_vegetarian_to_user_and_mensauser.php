<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVegetarianToUserAndMensauser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('vegetarian');
        });
        Schema::table('mensa_users', function (Blueprint $table) {
            $table->boolean('vegetarian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('vegetarian');
        });
        Schema::table('mensa_users', function (Blueprint $table) {
            $table->dropColumn('vegetarian');
        });
    }
}
