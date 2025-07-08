<?php

namespace App\Models;

use App\Models\Workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MuscleGroup;

class Exercise extends Model
{

    protected $fillable = [
        'name',
        'category',
        'muscle_group',
        'video_url',
        'difficulty_level',
        'calories_burned',
        'duration_seconds',
        'intensity',
    ];
    /** @use HasFactory<\Database\Factories\ExerciseFactory> */
    use HasFactory;
  public function workouts()
{
    return $this->belongsToMany(Workout::class, 'exercise_workout')
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

public function aliases()
{
    return $this->hasMany(ExerciseAlias::class);
}

public function muscleGroups(){

    return $this->belongsToMany(MuscleGroup::class , "exercise_muscle_group")
    ->withPivot(['role']);

}

}
