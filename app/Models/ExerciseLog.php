<?php

namespace App\Models;
use App\Models\SetLog;
use App\Models\WorkoutLog;
use App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;

class ExerciseLog extends Model
{
        protected $fillable = [
    'workout_log_id',
    'exercise_id',
    'name_snapshot',
    'order',
    'calories_burned',
    'duration_seconds',
];

    public function setsLogs(){
        return $this->hasMany(SetLog::class);
    }


    public function workoutLog(){
        return $this->belongeTo(WorkoutLog::class);
    }
      public function exercises(){
        return $this->belongsTo(Exercises::class);
    }
}
