<?php

namespace App\Models;
use\Models\User;

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
        return $this->hasMany(Exercise::class);
    }
}
