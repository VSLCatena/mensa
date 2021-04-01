<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_options', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('mensa_id')->references('id')->on('mensas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('price');

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extra_options');
    }
}
