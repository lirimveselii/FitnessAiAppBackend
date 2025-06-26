<?php

namespace App\Models;

use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MealPlan extends Model
{
    protected $fillable = [
    'user_id',
    'title',
    'description',
    'calories',
    'protein_g',
    'carbs_g',
    'fats_g',
    'day_meal',
    'meal_type',
    'ingredients',
];
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
