<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensaExtraOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensa_extra_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mensa_id')->unsigned();
            $table->string('description');
            $table->decimal('price');

            $table->foreign('mensa_id')->references('id')->on('mensas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mensa_extra_options');
    }
}
