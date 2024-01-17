<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensaUserExtraOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensa_user_extra_options', function (Blueprint $table) {
            $table->integer('mensa_user_id')->unsigned();
            $table->integer('mensa_extra_option_id')->unsigned();

            $table->foreign('mensa_user_id')->references('id')->on('mensa_users')->onDelete('cascade');
            $table->foreign('mensa_extra_option_id')->references('id')->on('mensa_extra_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mensa_user_extra_options');
    }
}
