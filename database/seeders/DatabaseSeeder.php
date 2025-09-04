<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ExerciseSeeders;
use Database\Seeders\MuscleGroupSeeder;
use Database\Seeders\FoodSeeder;
use App\Models\AiRecommendation;
use App\Models\Workout;
use App\Models\Exercise;
use App\Models\MealPlan;
use App\Models\Food;
use App\Models\UserProgress;
use App\Models\UserPreference;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        AiRecommendation::factory(10)->create();
        // Food::factory()->count(10)->create();
        UserProgress::factory()->count(10)->create();
        UserPreference::factory()->count(10)->create();

         $this->call([
        ExerciseSeeder::class,
        MuscleGroupSeeder::class,
        FoodSeeder::class


        ]);
     
    }
}
