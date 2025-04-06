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
        Schema::table('exercises', function (Blueprint $table) {
            Schema::table('exercises', function (Blueprint $table) {
                $table->dropColumn('sets');
                $table->dropColumn('reps');
                $table->dropColumn('rest_seconds');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            Schema::table('exercises', function (Blueprint $table) {
                $table->integer('sets')->check('sets > 0');
                $table->integer('reps')->check('reps > 0');
                $table->integer('rest_seconds')->default(30);
            });
        });
    }
};
