<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('lidnummer')->index();
            $table->string('name');
            $table->string('email');
            $table->string('allergies')->nullable();
            $table->string('wishes')->nullable();
            $table->boolean('mensa_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();

            $table->primary('lidnummer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
