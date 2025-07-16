<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Goal;

class GoalController extends Controller
{
         public function store(Request $request)
    {
         $request->validate([
        'goal_type'     => 'required|string',
        'target_value'  => 'required|numeric|min:0',
        'current_value' => 'nullable|numeric|min:0',
        'unit'          => 'required|string',
        'start_date'    => 'nullable|date',
        'end_date'      => 'nullable|date|after_or_equal:start_date',
    ]);
        
           $goal = Goal::create([
            'user_id' => 1,
            'goal_type' => $request->goal_type,
            'target_value'  => $request->target_value,
            'current_value' => $request->current_value ?? 0,
            'unit'          => $request->unit,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
        ]);

    return response()->json($goal, 201);
    }

    /**
     * Show a specific goal for the authenticated user.
     */
    public function show($id)
    {
        $goal = Goal::all();

        return response()->json($goal);
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
}

