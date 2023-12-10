<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
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
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
