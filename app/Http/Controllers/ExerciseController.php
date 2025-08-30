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
    $exercises = Exercise::whereHas('muscleGroups', function ($query) use ($muscleGroups) {
        if (!empty($muscleGroups)) {
            $query->whereIn('region', $muscleGroups);
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


    public function searchExercise(Request $request)
     {
        $request->validate([
            'q' => 'required|string|max:120',
            'per_page' => 'sometimes|integer|min:1|max:50',
        ]);

        $term = $request->input('q');
        $perPage = (int) $request->input('per_page', 20);

        $query = Exercise::query()
            ->with(['aliases:id,exercise_id,alias']) // eager-load aliases
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhereHas('aliases', function ($qa) use ($term) {
                      $qa->where('alias', 'like', "%{$term}%");
                  });
            })
            ->distinct();

        // (Optional) simple relevance: exact name match first, then alias, then partials
        $query->orderByRaw("
            (CASE
              WHEN name = ? THEN 0
              WHEN name LIKE ? THEN 1
              ELSE 2
            END), name ASC
        ", [$term, "{$term}%"]);

        $results = $query->paginate($perPage)->appends($request->query());

        return response()->json([
            'data' => $results->items(),
            'meta' => [
                'page' => $results->currentPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
            ],
        ]);
    }

}
