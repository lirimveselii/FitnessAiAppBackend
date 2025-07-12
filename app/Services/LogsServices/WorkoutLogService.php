<?php

namespace App\Services\LogsServices;
use App\Models\WorkoutLog;
use App\Models\ExerciseLog;
use App\Models\SetLog;



class WorkoutLogService
{

    public function storeWorkoutLogs(int $userId, String $workoutName, $status , $exercises ,$satretdAt , $endedAt){

        $totalCaloriesBurned = 0;  

    $workout =  WorkoutLog::create([
        'user_id' => $userId,
        'workout_name' => $workoutName,
        'started_at' => $satretdAt,
        'ended_at' => $endedAt,
        'status' =>$status,
    ]);
    $workoutId = $workout->id;


    foreach($exercises as $exercise){

    $exerciseLog =  ExerciseLog::create([
            'workout_log_id' => $workoutId,
            'exercise_id' => $exercise['exercise_id'],
            'name_snapshot' => $exercise['name_snapshot'],
            'order' => $exercise['order'],
            'calories_burned' => $exercise['calories_burned'],
            'duration_seconds' => $exercise['duration_seconds'],
        ]);

        $totalCaloriesBurned += $exercise['calories_burned'];

        $exerciseLogId = $exerciseLog->id;
        
        foreach( $exercise["sets"] as $set ) {

            SetLog::create([

        'exercise_log_id'=>$exerciseLogId,
        'set_number'=> $set["set_number"],
        'reps' => $set["reps"] ,
        'weight'=> $set["weight"],
            ]);

        }
    }
     $workout->update([
        'calories_burned' => $totalCaloriesBurned,
    ]);

    }

}