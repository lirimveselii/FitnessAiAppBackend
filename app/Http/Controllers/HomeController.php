<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\Workout;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;


class HomeController extends Controller
{
   public function todaysMeals()
{
    // Merr ditën e sotme (p.sh. Monday, Tuesday...)
    $today = Carbon::now()->format('l'); // returns full day name (e.g. Monday)
    $now = Carbon::now('Europe/Skopje');
    $currentTime = $now->format('h:i:s A'); 
    
    // $userId = Auth::id(); // ose vendose manualisht nese je duke testu

    // Merr vakte për atë ditë dhe për userin (nëse nevojitet)
    $meals = MealPlan::where('day_meal', $today)
                     ->where('user_id', 27) // hiqe nëse s’ke user login
                     ->get();


    return response()->json([
        $currentTime,
        $today,
        $meals
    ]);
}

    public function currentTodayWorkout()
    {
        $workout = Workout::whereDate('date', now())->first();

        return response()->json([
            'title' => $workout->title ?? 'Leg Day',
            'duration' => $workout->duration ?? '45 min',
            'workout' => [
                'title' => $workout->name ?? 'Leg workout',
                'description' => $workout->description ?? 'Strengthen and sculpt your lower body...',
                'exercises' => $workout->exercise_count ?? 5
            ]
        ]);
    }
}
