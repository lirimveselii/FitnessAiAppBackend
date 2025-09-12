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
          Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();           // e.g. 'calories', 'water', 'workouts'
            $table->string('title');                    // 'Daily Calories', 'Water Intake'
            $table->text('description')->nullable();
            $table->string('metric');                   // e.g. 'calories_consumed','water_ml','workouts_completed'
            $table->float('default_target_value')->nullable(); // e.g. 2000 (calories), 2000 (ml), 3 (workouts)
            $table->string('unit');                     // e.g. 'kcal','ml','count'
            $table->json('meta')->nullable();           // any extra rules / filters
            $table->timestamps();
    });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
    