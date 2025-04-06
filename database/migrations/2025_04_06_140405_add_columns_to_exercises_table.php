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
                $table->string('category')->nullable()->after('name');  
                $table->string('muscle_group')->nullable()->after('category');  
                $table->string('equipment')->nullable()->after('muscle_group'); 
                $table->text('description')->nullable()->after('equipment');  
                $table->string('video_url')->nullable()->after('description');  
                $table->string('difficulty_level')->nullable()->after('video_url'); 
                $table->decimal('calories_burned', 8, 2)->nullable()->after('difficulty_level'); 
                $table->integer('duration_seconds')->nullable()->after('calories_burned'); 
                $table->string('intensity')->nullable()->after('duration_seconds');  
                $table->string('tags')->nullable()->after('intensity');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'muscle_group',
                'equipment',
                'description',
                'video_url',
                'difficulty_level',
                'calories_burned',
                'duration_seconds',
                'intensity',
                'tags',
            ]);
        });
    }
};
