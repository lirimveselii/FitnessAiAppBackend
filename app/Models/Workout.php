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

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'workout_day',
        'duration_min',
        'intensity_level',
        'workout_type',
        'calories_burned',
        'target_muscle_groups',
        'notes',
        'status',
        'workout_date',
        'difficulty_level',
        'progress_results',
        'tags',
        'rating',
    ];
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
public function exercises()
{
    return $this->belongsToMany(Exercise::class, 'exercise_workout')
                ->withPivot([
                    'sets',
                    'reps',
                    'rest_seconds',
                    'duration_seconds',
                    'order',
                    'notes'
                ])
                ->withTimestamps();
}

}
