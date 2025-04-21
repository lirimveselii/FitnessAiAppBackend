<?php

namespace Database\Factories;
use App\Models\Workout;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $equipmentOptions = ['dumbbell', 'barbell', 'kettlebell', 'none', 'resistance_band', 'treadmill', 'elliptical', 'rowing_machine', 'medicine_ball'];
        $categoryOptions = ['strength', 'cardio', 'flexibility', 'endurance', 'hybrid'];
        $muscleGroupOptions = ['chest', 'legs', 'arms', 'back', 'shoulders', 'core', 'full_body'];
        $difficultyLevels = ['beginner', 'intermediate', 'advanced'];
        $intensityLevels = ['low', 'moderate', 'high'];
        $tagsOptions = ['strength', 'arms', 'legs', 'cardio', 'HIIT', 'core', 'endurance', 'full_body'];
        $workoutId = Workout::inRandomOrder()->first()->id; 
        return [
            
            'name' => $this->faker->word, // Random exercise name
            'category' => $this->faker->randomElement($categoryOptions),  // Randomly select a category
            'workout_id' => $workoutId, 
            'muscle_group' => $this->faker->randomElement($muscleGroupOptions),  // Randomly select a muscle group
            'equipment' => $this->faker->randomElement($equipmentOptions),  // Randomly select an equipment
            'description' => $this->faker->text(200),  // Description of the exercise
            'sets' => fake()->numberBetween(1, 5),
            'reps' => fake()->numberBetween(5, 20),
            'rest_seconds' => fake()->numberBetween(15, 90),
            'video_url' => $this->faker->url(),  // URL for exercise video (could be a YouTube link)
            'difficulty_level' => $this->faker->randomElement($difficultyLevels),  // Randomly select a difficulty level
            'calories_burned' => $this->faker->randomFloat(2, 10, 100),  // Random float for calories burned
            'duration_seconds' => $this->faker->numberBetween(30, 600),  // Random duration in seconds
            'intensity' => $this->faker->randomElement($intensityLevels),  // Randomly select an intensity
            'tags' => implode(', ', $this->faker->randomElements($tagsOptions, 3)),  // Comma-separated tags, select 3 random tags
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
