<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class ManualMealLog extends Model
{


  protected $fillable = [
    'user_id',
    'source',
    'meal_type',
    'log_date',
    'calories',
    'carbs',
    'fats',
    'protein',
];

public function user()
{
    return $this->belongsTo(User::class);
}
    
}
