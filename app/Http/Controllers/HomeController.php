<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\Workout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class HomeController extends Controller
{
    public function todaysMeals()
    {
        // Merr ditën e sotme (p.sh. Monday, Tuesday...)
        $today = Carbon::now()->format('l'); // returns full day name (e.g. Monday)
        $now = Carbon::now('Europe/Skopje');
        $currentTime = $now->format('h:i:s A');

        $userId = Auth::id() ?? 1; // fallback for now

        $meals = MealPlan::where('day_meal', $today)
            ->where('user_id', $userId)
            ->orderBy('meal_type')
            ->get();


        return response()->json([
            $currentTime,
            $today,
            $meals
        ]);
    }

    public function currentTodayWorkout()
    {
        $userId = Auth::id() ?? 1; // fallback for now
        $workout = Workout::whereDate('workout_date', Carbon::today())
            ->where('user_id', $userId)
            ->withCount('exercises')
            ->first();

        return response()->json([
            'title' => $workout->title ?? 'Leg Day',
            'duration' => isset($workout->duration_min) ? ($workout->duration_min . ' min') : '45 min',
            'workout' => [
                'title' => $workout->title ?? 'Leg workout',
                'description' => $workout->description ?? 'Strengthen and sculpt your lower body...',
                'exercises' => $workout->exercises_count ?? 5
            ]
        ]);
    }

    public function updateCalories(Request $request)
    {
        $validated = $request->validate([
            'calories' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        if (!$user || !$user->goal) {
            return response()->json(['message' => 'Goal not found for user.'], 404);
        }

        $user->goal->calories_burned += (float) $validated['calories'];
        $user->goal->save();

        if ($user->goal->calories_burned >= $user->goal->calories_goal) {
            return response()->json(['message' => 'Congratulations! You have reached your calorie goal.']);
        }

        return response()->json(['message' => 'Calories added successfully.']);
    }

}
