<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

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
            $table->string('category')->after('updated_at');
            $table->dropForeign(['mensa_id']);
            $table->renameColumn('mensa_id', 'object_id');
            $table->dropIndex('logs_mensa_id_foreign');
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
            $table->renameColumn('object_id', 'mensa_id');
            $table->dropColumn('category');
        });
        DB::unprepared('ALTER TABLE `logs` ADD CONSTRAINT `logs_mensa_id_foreign` FOREIGN KEY (`mensa_id`) REFERENCES `mensas`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;'); #unable to do this with Schema without dropping table.
    }
};
