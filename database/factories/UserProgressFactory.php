<?php

namespace Database\Factories;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProgress>
 */
class UserProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           
               
                'user_id' => User::factory(), // Dynamic foreign key (user_id)
                'weight_kg' => $this->faker->randomFloat(2, 40, 150), // Random weight (kg)
                'bmi' => $this->faker->randomFloat(2, 15, 40), // Random BMI value
                'workout_streak' => $this->faker->numberBetween(0, 100), // Random workout streak count
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
    }
}
