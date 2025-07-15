<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DietDay;
use App\Models\MealIngredient;

class Meal extends Model
{
    public function day()
{
    return $this->belongsTo(DietDay::class, 'diet_day_id');
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by_user_id');
}

public function ingredients()
{
    return $this->hasMany(MealIngredient::class);
}

}
