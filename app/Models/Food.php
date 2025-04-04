<?php

namespace App\Models;

use App\Models\MealPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    /** @use HasFactory<\Database\Factories\FoodFactory> */
    use HasFactory;
    
    public function mealPlan() 
    {
        return $this->belongsTo(MealPlan::class);
    }

}
