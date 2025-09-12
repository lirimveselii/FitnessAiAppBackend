<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class MealLog extends Model
{


  protected $fillable = [
    'title',
    'user_id',
    'source',
    'meal_type',
    'log_date',
    'calories',
    'carbs',
    'fats',
    'protein',
    "date"
];

  protected $casts = [
        'meta' => 'array', // or 'json'
    ];

public function user()
{
    return $this->belongsTo(User::class);
}
    
}
