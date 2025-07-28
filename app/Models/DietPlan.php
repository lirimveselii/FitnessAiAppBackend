<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DietDay;
use App\Models\User;


class DietPlan extends Model
{

  protected $fillable = [
    'user_id',
    'goal',
    'start_date',
    'duration_days',
    'version',
    'is_active',
    'is_manual',
    'source',
];
  public function user()
{
    return $this->belongsTo(User::class);
}

public function days()
{
    return $this->hasMany(DietDay::class);
}
}
