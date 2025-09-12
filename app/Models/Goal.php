<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
      protected $fillable = [
        'code',
        'title',
        'description',
        'metric',
        'default_target_value',
        'unit',
        'meta',
    ];


      public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'goal_user')
            ->withPivot(['is_active', 'starts_at', 'ends_at', 'recurrence'])
            ->withTimestamps();
    }
}