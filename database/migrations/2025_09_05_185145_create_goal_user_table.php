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
        Schema::create('goal_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('goal_id')->constrained('goals')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable(); // when the assignment becomes active
            $table->timestamp('ends_at')->nullable();   // when it stops being considered
            $table->unsignedInteger('target_value')->nullable();
            $table->enum('recurrence', ['daily','weekly','monthly','once'])->default('daily');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_user');
    }
};
