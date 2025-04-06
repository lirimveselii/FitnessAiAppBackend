<?php

namespace App\Models;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workout extends Model
{
    /** @use HasFactory<\Database\Factories\WorkoutFactory> */
    use HasFactory;

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'workout_exercise')
                    ->withPivot('sets', 'reps', 'rest_seconds');
    }
}
