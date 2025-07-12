<?php

namespace App\Models;
use App\Models\ExerciseLog;
use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
     protected $fillable = ["user_id","workout_name","started_at","ended_at","status","calories_burned_total"];
 
    public function exercisesLogs(){
        return $this->hasMany(ExerciseLog::class);
    }
}
