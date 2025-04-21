<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreference>
 */
class UserPreferenceFactory extends Factory
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
            'dietary_restrictions' => $this->faker->sentence(), // Random dietary restrictions (optional)
            'preferred_cuisine' => $this->faker->word, // Random cuisine preference
            'workout_type' => $this->faker->randomElement(['strength', 'cardio', 'yoga', 'mixed']), // Random workout type
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
    }
}
