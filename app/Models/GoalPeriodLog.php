<?php

namespace App\Models;
use App\Models\User;
use App\Models\Goal;

use Illuminate\Database\Eloquent\Model;

class GoalPeriodLog extends Model
{
    // Laravel will infer table name 'goal_period_logs'
    // If you used a different name, add: protected $table = 'goal_period_logs';

    protected $fillable = [
        'user_id',
        'goal_id',
        'period_start',
        'period_end',
        'target_value',
        'actual_value',
        'completion_ratio',
        'status',
    ];

    protected $casts = [
        'period_start'     => 'datetime',
        'period_end'       => 'datetime',
        'target_value'     => 'decimal:2',
        'actual_value'     => 'decimal:2',
        'completion_ratio' => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
