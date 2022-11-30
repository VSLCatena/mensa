<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropForeign(['mensa_id']);
            $table->dropColumn('mensa_id');
            $table->int('severity')->default(5)->after('updated_at');
            $table->enum('category', ['model','mensa','mail'])->after('severity');
            $table->int('visibility')->after('category');
            $table->nullableUuidMorphs('loggable');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {        
            $table->dropMorphs('loggable');
            $table->dropColumn('severity');
            $table->dropColumn('category');
            $table->foreignUuid('mensa_id')->references('id')->on('mensas')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }
};
