<?php

namespace App\Models;

use App\Models\Workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsTo(Workout::class);
    }
}
