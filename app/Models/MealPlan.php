<?php

namespace App\Models;

use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MealPlan extends Model
{
    /** @use HasFactory<\Database\Factories\MealPlanFactory> */
    use HasFactory;
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function Foods()
    {
        return $this->hasMany(Food::class);
    }
}
