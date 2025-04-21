<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MealPlan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
            
            return [
                
                'meal_plan_id' => MealPlan::factory(), // Dynamic foreign key (meal_plan_id)
                'name' => $this->faker->word, // Random food name
                'serving_size' => $this->faker->word, // Random serving size (e.g., "1 cup", "100g", etc.)
                'calories' => $this->faker->numberBetween(50, 800), // Random calories
                'protein_g' => $this->faker->randomFloat(2, 1, 50), // Random protein amount (grams)
                'carbs_g' => $this->faker->randomFloat(2, 5, 100), // Random carbs amount (grams)
                'fats_g' => $this->faker->randomFloat(2, 1, 50), // Random fats amount (grams)
                'created_at' => now(),
                'updated_at' => now(),
            ];
        
    }
}
