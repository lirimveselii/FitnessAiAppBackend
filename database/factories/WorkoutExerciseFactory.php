<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Workout;
use App\Models\Exercise;
use App\Models\WorkoutExercise;  

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutExercise>
 */
class WorkoutExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\WorkoutExercise::class;
    public function definition(): array
    {

         // Get existing workout and exercise IDs from the database
         $workoutId = Workout::inRandomOrder()->first()->id;  // Select a random existing workout ID
         $exerciseId = Exercise::inRandomOrder()->first()->id;  // Select a random existing exercise ID
 
         return [
             'workout_id' => $workoutId,
             'exercise_id' => $exerciseId,
             'sets' => $this->faker->numberBetween(3, 5),  // Number of sets
             'reps' => $this->faker->numberBetween(8, 12),  // Number of reps
             'rest_time' => $this->faker->numberBetween(30, 90),  // Rest time in seconds
         ];
    }
}
