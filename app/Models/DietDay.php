<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DietPlan;
use App\Models\Meal;

class DietDay extends Model
{
public function plan()
{
    return $this->belongsTo(DietPlan::class, 'diet_plan_id');
}

public function meals()
{
    return $this->hasMany(Meal::class);
}

}
