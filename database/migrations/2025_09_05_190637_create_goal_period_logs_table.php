<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. Kllxpm kur t shej knej 
     */
    public function up(): void
    {
        Schema::create('goal_period_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('goal_id')->constrained()->onDelete('cascade');
            $table->dateTime('period_start'); // inclusive
            $table->dateTime('period_end');   // exclusive
            $table->decimal('target_value', 10, 2);
            $table->decimal('actual_value', 10, 2)->default(0);
            $table->decimal('completion_ratio', 6, 4)->nullable(); // actual / target
            $table->enum('status', ['met','missed','partial','skipped'])->default('partial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_period_logs');
    }
};
