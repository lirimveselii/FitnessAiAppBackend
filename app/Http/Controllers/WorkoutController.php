<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\AIService;

class WorkoutController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService){

        $this->aiService = $aiService ;
    }

    public function generateWorkoutPlan(Request $request){

        $prount = $request->prount;

        $workoutData = $this->aiService->getWorkoutPlan($prount);

        dd($workoutData);

    }
    


}
