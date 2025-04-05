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
                'created_at' => now(),
                'updated_at' => now(),
            
        ];
    }
}
