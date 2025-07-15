<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFoodLibrary extends Model
{
 protected $table = 'custom_food_library';

public function user()
{
    return $this->belongsTo(User::class);
}
}
