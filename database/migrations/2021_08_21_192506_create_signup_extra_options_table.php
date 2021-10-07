<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignupExtraOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signup_extra_options', function (Blueprint $table) {
            $table->timestampsTz();
            $table->foreignId('signup_id')->references('id')->on('signups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('extra_option_id')->references('id')->on('extra_options')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(array('signup_id', 'extra_option_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signup_extra_options');
    }
}
