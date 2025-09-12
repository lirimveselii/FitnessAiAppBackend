<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Goal;
use App\Models\User;
use Carbon\Carbon;
use App\Services\LogsServices\GoalLogService;

class GoalController extends Controller
{


    public function userGoals()
{
    $user = auth()->user() ?? User::find(1);

    $goals = $user->goals()
        ->withPivot(['is_active', 'starts_at', 'ends_at', 'recurrence', 'target_value'])
        ->get();

    return response()->json($goals);
}


    public function store(Request $request)
{
    $user = auth()->user() ?? User::find(1); // authenticated user

    $validated = $request->validate([
        'code'             => 'required|string',         // e.g. "water", "calories", "workouts"
        'title'            => 'required|string',
        'metric'           => 'required|string',         // e.g. "ml", "kcal"
        'default_value'    => 'required|numeric|min:0',
        'unit'             => 'required|string',
        'meta'             => 'nullable',
        'starts_at'        => 'nullable|date',
        'ends_at'          => 'nullable|date|after_or_equal:starts_at',
        'recurrence'       => 'required|in:daily,weekly,monthly,once',
        'user_target_value' => 'nullable|numeric|min:0',
    ]);

        $startsAt = $request->filled('starts_at')
        ? Carbon::parse($request->input('starts_at')) // accepts "2025-09-11T08:00:00Z"
        : now();

    $endsAt = $request->filled('ends_at')
        ? Carbon::parse($request->input('ends_at'))
        : null;

    // Create or get goal definition
    $goal = Goal::firstOrCreate(
        ['code' => $validated['code']],
        [
            'title'                => $validated['title'],
            'metric'               => $validated['metric'],
            'default_target_value' => $validated['default_value'],
            'unit'                 => $validated['unit'],
            'meta'                 => json_encode($validated['meta'] ?? []),
        ]
    );

    // Attach to user with pivot data
    $user->goals()->syncWithoutDetaching([
        $goal->id => [
            'is_active'  => true,
            'starts_at'  => $startsAt ?? now(),
            'ends_at'    => $endsAt ?? null,
            'recurrence' => $validated['recurrence'],
            'target_value' => $validated['user_target_value'] ?? $goal->default_target_value,
        ]
    ]);

    return response()->json([
        'message' => 'Goal created and assigned successfully.',
        'goal'    => $goal
    ], 201);
}

    /**
     * Update an existing goal.
     */
   public function update(Request $request, $id)
    {
        $goal = Goal::where('id', $id)
                    ->where('user_id', 1)
                    ->firstOrFail();

        $request->validate([
            'goal_type'     => 'required|string',
            'target_value'  => 'required|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'unit'          => 'required|string',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
        ]);

        $goal->update([
            'goal_type'     => $request->goal_type,
            'target_value'  => $request->target_value,
            'current_value' => $request->current_value ?? $goal->current_value,
            'unit'          => $request->unit,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
        ]);

        return response()->json($goal);
    }

    /**
     * Delete a goal.
     */
    public function destroy($id)
    {
        $goal = Goal::where('id', $id)
                    ->where('user_id', 1)
                    ->firstOrFail();

        $goal->delete();

        return response()->json(['message' => 'Goal deleted successfully']);
    }

public function storeGoalLog(Request $request)
    {
     
       $log = app(GoalLogService::class)->upsert($request->all()); // uses auth user or ID=1 fallback
    return response()->json(['message' => 'stored', 'log' => $log], 201);
    }

}

