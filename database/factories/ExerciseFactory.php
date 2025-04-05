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
        return [
            
            'workout_id' => Workout::factory(), // Dynamic foreign key (workout_id)
            'name' => $this->faker->word, // Random exercise name
            'sets' => $this->faker->numberBetween(3, 5), // Random number of sets
            'reps' => $this->faker->numberBetween(8, 15), // Random number of reps
            'rest_seconds' => $this->faker->numberBetween(30, 90), // Random rest time in seconds
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
