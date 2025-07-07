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
             $table->dropColumn([
                'workout_id',
                'sets',
                'reps',
                'rest_seconds',
                'duration_seconds'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->foreignId('workout_id')->nullable()->constrained()->onDelete('cascade');
            $table->tinyInteger('sets')->nullable();
            $table->tinyInteger('reps')->nullable();
            $table->tinyInteger('rest_seconds')->nullable();
            $table->tinyInteger('duration_seconds')->nullable();
        });
    }
};
