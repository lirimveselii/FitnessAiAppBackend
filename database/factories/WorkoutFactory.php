<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workout>
 */
class WorkoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           
               
                'user_id' => User::factory(), // Dynamic foreign key
                'title' => $this->faker->sentence(3),
                'description' => $this->faker->paragraph(),
                'duration_min' => $this->faker->numberBetween(10, 90),
                'intensity_level' => $this->faker->randomElement(['low', 'moderate', 'high']),
                'title' => $this->faker->sentence(),
                'description' => $this->faker->paragraph(),
                'duration_min' => $this->faker->numberBetween(30, 120),  // Random workout duration between 30 and 120 minutes
                'intensity_level' => $this->faker->randomElement(['low', 'moderate', 'high']),
                'workout_type' => $this->faker->randomElement(['cardio', 'strength', 'flexibility', 'endurance']),
                'calories_burned' => $this->faker->randomFloat(2, 100, 1000),  // Random number of calories burned
                'target_muscle_groups' => $this->faker->randomElement(['chest', 'legs', 'arms', 'back', 'shoulders']),
                'notes' => $this->faker->optional()->paragraph(),
                'status' => $this->faker->randomElement(['planned', 'in_progress', 'completed']),
                'workout_date' => $this->faker->date(),
                'difficulty_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
                'progress_results' => $this->faker->optional()->paragraph(),
                'tags' => $this->faker->words(3, true),  // Random tags
                'rating' => $this->faker->numberBetween(1, 5),  // Rating between 1 and 5
                'created_at' => now(),
                'updated_at' => now(),
            
        ];
    }
}
