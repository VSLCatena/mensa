<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMensasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensas', function (Blueprint $table) {
            $table->uuid('id');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->string('title');
            $table->text('description');
            $table->integer('date');
            $table->integer('closing_time');
            $table->unsignedTinyInteger('max_signups');
            $table->tinyInteger('food_options')->unsigned();
            $table->boolean('closed')->default(false);
            $table->float('price');

            $table->primary('id');

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
        Schema::dropIfExists('mensas');
    }
}
