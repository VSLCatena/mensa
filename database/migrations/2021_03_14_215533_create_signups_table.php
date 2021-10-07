<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('signup_id');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->boolean('cooks');
            $table->boolean('dishwasher');
            $table->tinyInteger('food_option')->unsigned();
            $table->boolean('is_intro');
            $table->string('allergies')->nullable();
            $table->string('extra_info')->nullable();
            $table->boolean('confirmed');
            $table->double('paid')->default(0.0);
            $table->uuid('confirmation_code');

            $table->foreignUuid('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('mensa_id')->references('id')->on('mensas')->cascadeOnUpdate()->cascadeOnDelete();

            $table->index('signup_id');
            $table->index('confirmed');
            $table->index('created_at');
            $table->index('deleted_at');
            $table->index('confirmation_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signups');
    }
}
