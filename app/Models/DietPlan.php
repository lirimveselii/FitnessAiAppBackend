<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DietDay;
use App\Models\User;


class DietPlan extends Model
{
  public function user()
{
    return $this->belongsTo(User::class);
}

public function days()
{
    return $this->hasMany(DietDay::class);
}
}
