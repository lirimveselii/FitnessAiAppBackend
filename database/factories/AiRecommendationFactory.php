<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiRecommendation>
 */
class AiRecommendationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            return [
                'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
                'recommendation_type' => $this->faker->randomElement(['workout', 'meal_plan', 'health_tip']),
                'recommendation' => $this->faker->sentence(10),
            ];
        
    }
}
