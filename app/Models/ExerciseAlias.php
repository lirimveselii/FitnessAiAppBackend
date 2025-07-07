<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises;

class ExerciseAlias extends Model
{
    
protected $fillable =['exercise_id',"alias"];

    public function exercise(){
        return $this->belongsTo(Exercises::class);
    }

}
