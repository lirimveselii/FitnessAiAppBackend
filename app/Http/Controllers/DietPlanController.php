<?php

namespace App\Http\Controllers;
use App\Services\AIService;
use App\Services\DietPlan\AiDietStoreService;

use Illuminate\Http\Request;

class DietPlanController extends Controller
{
 

     
     protected AIService $aiService;

    public function __construct(AIService $aiService){

        $this->aiService = $aiService;
    }


    public function generateDiet( Request $request){

        $data = $request->all();
        $plan = $this->aiService->getDietPlan($data);
        dd($plan);
        $storeDiet = app(AiDietStoreService::class)->store($plan);

    }

}
