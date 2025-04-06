<?php

namespace App\Models;

use App\Models\Workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exercise extends Model
{
    /** @use HasFactory<\Database\Factories\ExerciseFactory> */
    use HasFactory;
    public function workouts()
    {
        return $this->belongsToMany(Workout::class, 'workout_exercise')
                    ->withPivot('sets', 'reps', 'rest_seconds');
    }
}
