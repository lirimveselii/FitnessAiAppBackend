<?php

namespace App\Services\LogsServices;

use App\Models\Goal;
use App\Models\GoalPeriodLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class GoalLogService
{
    public function upsert(array $input, ?User $user = null)
    {
          
        $user = $user ?? auth()->user() ?? User::findOrFail(1);
        $data = $input;
            // dd("res");
            
        // Resolve goal_id (prefer explicit id, else by code)
        if (empty($data['goal_id'])) {
            $data['goal_id'] = Goal::where('code', $data['goal_code'])->value('id');
        }
        // dd($data['goal_id']);

        // Normalize to UTC
        $startUtc = Carbon::parse($data['period_start'])->timezone('UTC');
        $endUtc   = Carbon::parse($data['period_end'])->timezone('UTC');

        $target = (float) $data['target_value'];
        $actual = (float) $data['actual_value'];

        // Derive ratio if omitted
        $ratio = array_key_exists('completion_ratio', $data) && $data['completion_ratio'] !== null
            ? (float) $data['completion_ratio']
            : ($target > 0 ? round(min($actual / $target, 9999.9999), 4) : null);

        // Derive status if omitted
        $status = $data['status'] ?? $this->deriveStatus($target, $actual, $ratio);
        $res = GoalPeriodLog::create(
            [
                'user_id'      => $user->id,
                'goal_id'      => $data['goal_id'],
                'period_start' => $startUtc,
                'period_end'   => $endUtc,
                'target_value'     => $target,
                'actual_value'     => $actual,
                'completion_ratio' => $ratio,
                'status'           => $status,
            ]
        );
        
    }

    protected function deriveStatus(float $target, float $actual, ?float $ratio): string
    {
        if ($target <= 0) return 'skipped';
        if ($actual == 0) return 'skipped';
        if ($ratio === null) return 'partial';
        if ($ratio >= 1) return 'met';
        if ($ratio >= 0.5) return 'partial';
        return 'missed';
    }

}
