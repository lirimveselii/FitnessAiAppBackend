<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AIService;

class FoodController extends Controller
{
      protected AIService $aiService;

    public function __construct(AIService $aiService){

        $this->aiService = $aiService;
    }


    public function generateDiet( Request $request){

        $data = $request->all();
        $plan = $this->aiService->getDietPlan($data);


dd($plan);

    }
}
