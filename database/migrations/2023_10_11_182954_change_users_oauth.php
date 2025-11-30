<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->uuid('id')->first();
           $table->dropPrimary('lidnummer');
           $table->primary('id');
        });

        Schema::table('mensa_users', function (Blueprint $table) {
           $table->dropForeign('mensa_users_lidnummer_foreign');
//           $table->uuid('user_id')->after('id');
//           $table->foreign('user_id')->references('id')->on('users');
           $table->foreignUuid('user_id')->constrained(); 
        });

        Schema::table('faqs', function (Blueprint $table) {
           $table->dropForeign('faqs_last_edited_by_foreign');
        });
        Schema::table('faqs', function (Blueprint $table) {
           $table->uuid('last_edited_by')->change();
//           $table->foreign('last_edited_by')->references('id')->on('users');
        });
        Schema::table('logs', function (Blueprint $table) {
       //    $table->uuid('user_id')->after('mensa_id');
           $table->dropForeign('logs_lidnummer_foreign');
//           $table->foreign('user_id')->references('id')->on('users');
           $table->foreignUuid('user_id')->constrained(); 

        });

        Schema::table('users', function (Blueprint $table) {
// ALTER TABLE `users`
//    ADD `id` UUID NOT NULL AUTO_INCREMENT FIRST,
//    ADD `user_id` UUID NULL DEFAULT NULL AFTER `id`, ADD PRIMARY KEY (`id`);

//           $table->bigIncrements('id')->first(); //bigIncrement includes Primary key..unsignedbigInteger
           $table->dropIndex('users_lidnummer_index');
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->primary('lidnummer');
            $table->index('users_lidnummer_index');
        });

    }
};
