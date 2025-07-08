<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises;

class MuscleGroup extends Model
{
  
  
  protected $filable = ["region" , "muscle_group"];

  public function exercises(){ 
    return $this->belongsToMany(Exercises::class , "exercise_muscle_group")
      ->withPivot(['role']);
}
}
