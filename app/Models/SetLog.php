<?php

namespace App\Models;
use App\Models\ExerciseLog;
use Illuminate\Database\Eloquent\Model;

class SetLog extends Model
{
   protected $fillable = [
    'exercise_log_id',
    'set_number',
    'reps',
    'weight',
];

       public function exerciseLog(){
        return $this->belongesTo(ExerciseLog::class);
    }
}

