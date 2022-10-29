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
            // 0       Emergency: system is unusable
            // 1       Alert: action must be taken immediately
            // 2       Critical: critical conditions
            // 3       Error: error conditions
            // 4       Warning: warning conditions
            // 5       Notice: normal but significant condition
            // 6       Informational: informational messages
            // 7       Debug: debug-level messages            
            $table->enum('severity', [0,1,2,3,4,5,6,7])->default(6)->after('updated_at');
            $table->enum('category', ['mensa','user','mail'])->after('severity');
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
