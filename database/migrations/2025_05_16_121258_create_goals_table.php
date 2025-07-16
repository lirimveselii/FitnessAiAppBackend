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
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('goal_type'); 
        $table->float('target_value');
        $table->float('current_value')->default(0);
        $table->string('unit');
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->boolean('is_active')->default(true);
        $table->string('status')->default('in_progress');
        $table->timestamps();
    });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
    