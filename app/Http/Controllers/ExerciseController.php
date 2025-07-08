<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use Illuminate\Support\Facades\Log;
use App\Models\MuscleGroup;


class ExerciseController extends Controller
{

    public function getExercisesForWorkout($workoutId){

        dd($workoutId);
    }

    public function filterExercises(Request $request){

     $muscleGroups = $request->input('muscle_groups', []); 
    $role = $request->input('role'); 
// dd($muscleGroups);
    $exercises = Exercise::whereHas('muscleGroups', function ($query) use ($muscleGroups, $role) {
        if (!empty($muscleGroups)) {
            $query->whereIn('muscle_group', $muscleGroups);
        }

        if (!empty($role)) {
            $query->wherePivot('role', $role);
        }
    })->get();

    return response()->json($exercises); 
        

    }


    public function createExerciseToMuscleGroup(){

        $exercises = Exercise::select("id","name")->get();
        $muscleGroups = MuscleGroup::select("id","region", "muscle_group")->get();

     return view('admin.exercise-muscles.create', compact('exercises', 'muscleGroups'));

    }
public function storeExerciseToMuscleGroup(Request $request)
{
    $request->validate([
        'exercise_id' => 'required|exists:exercises,id',
        'muscle_group_ids' => 'required|array',
        'muscle_group_ids.*' => 'exists:muscle_groups,id',
        'roles' => 'array', // optional, but helpful
    ]);

    $exercise = Exercise::findOrFail($request->exercise_id);
    // dd($request->muscle_group_ids);

    $syncData = [];

    foreach ($request->muscle_group_ids as $muscleGroupId) {
        $role = $request->roles[$muscleGroupId] ?? null;

        $syncData[$muscleGroupId] = ['role' => $role];
    }

    // Sync with role info in pivot
    $exercise->muscleGroups()->sync($syncData);

    return redirect()->back()->with('success', 'Muscle groups connected with roles successfully!');
}

}
