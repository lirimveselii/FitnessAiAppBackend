<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LogsServices\WorkoutLogService;
use Carbon\Carbon;


class WorkoutLogController extends Controller
{
    public function storeWorkoutLogs(Request $request){

    $userId = $request->input('user_id');
    $workoutName = $request->input('workout_name');
    $startedAt = Carbon::parse($request->input('started_at'))->format('Y-m-d H:i:s');
    $endedAt = Carbon::parse($request->input('ended_at'))->format('Y-m-d H:i:s');
    $status = $request->input('status');
    $exercises = $request->input('exercises');
    //  dd($userId, $workoutName, $status , $exercises);


        $service = app(WorkoutLogService::class);
        $service->storeWorkoutLogs($userId, $workoutName, $status , $exercises, $startedAt, $endedAt);



    }
}
