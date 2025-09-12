<?php 
namespace App\Services\LogsServices;
use App\Models\MealLog;
use Auth;


class MealLogService {



public function createMealLog(array $data)
{
    // dd($data["title"]);
        $mealLog = MealLog::create([
            'user_id'   => Auth::id() ?? 1,
            'source'    => $data['source']    ?? 'user', 
            'meal_type' => $data['meal_type'] ?? 'breakfast',
            'log_date'  => now()->toDateString(),
            'date'      => now()->toDateString(),
            'calories'  => $data['calories'] ?? 0,
            'carbs'     => $data['carbs']    ?? 0,
            'fats'      => $data['fats']     ?? 0,
            'protein'   => $data['protein']  ?? 0,
            'title'     => $data['title']    ?? null,
        ]);
        return $mealLog;
        
}
}

?>