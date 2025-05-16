<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MealPlan>
 */
class MealPlanFactory extends Factory
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
        'title' => $this->faker->sentence(4), // Random meal plan title
        'description' => $this->faker->paragraph(), // Random description
        'calories' => $this->faker->numberBetween(1500, 3500), // Random calories
        'protein_g' => $this->faker->randomFloat(2, 10, 200), // Random protein amount (grams) with 2 decimals
        'carbs_g' => $this->faker->randomFloat(2, 20, 300), // Random carbs amount (grams) with 2 decimals
        'fats_g' => $this->faker->randomFloat(2, 5, 100), // Random fats amount (grams) with 2 decimals
        'day_meal' => $this->faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']), // Random day
        'meal_type' => $this->faker->randomElement(['Breakfast', 'Lunch', 'Dinner', 'Snack']), // Random meal type
        'created_at' => now(),
        'updated_at' => now(),
    ];
        
    }
}
