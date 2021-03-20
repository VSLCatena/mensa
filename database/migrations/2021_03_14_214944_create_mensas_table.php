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
            $table->dateTimeTz('date');
            $table->dateTimeTz('closing_time');
            $table->unsignedTinyInteger('max_users');
            $table->boolean('closed')->default(false);

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
