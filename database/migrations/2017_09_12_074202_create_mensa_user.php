<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensaUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensa_users', function (Blueprint $table) {
            $table->string('lidnummer')->index();
            $table->integer('mensa_id')->unsigned()->index();
            $table->boolean('cooks');
            $table->boolean('dishwasher');
            $table->boolean('introduction');
            $table->string('allergies')->nullable();
            $table->string('wishes')->nullable();
            $table->timestamps();

            $table->foreign('lidnummer')->references('lidnummer')->on('users');
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
        //
    }
}
