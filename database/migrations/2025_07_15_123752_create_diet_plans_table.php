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
        Schema::create('diet_plans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('goal', ['weight_loss', 'muscle_gain', 'maintenance']) ->nullable();
                $table->date('start_date');
                $table->integer('duration_days');
                $table->integer('version')->default(1);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_manual')->default(false);
                $table->enum('source', ['ai', 'user', 'coach'])->default('ai');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_plans');
    }
};
