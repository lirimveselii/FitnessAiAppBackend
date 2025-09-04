<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;

class FoodController extends Controller
{
public function searchFood(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'query' => 'required|string|min:2|max:50',
    ]);

    $query = $validated['query'];

    // Build query: search in multiple columns
    $foods = Food::query()
        ->when($query, function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('category', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        })
        ->orderBy('name')
        ->limit(50)
        ->get();

    // Return structured JSON
    return response()->json([
        'success' => true,
        'message' => $foods->isEmpty()
            ? 'No foods found matching your query.'
            : 'Foods retrieved successfully.',
        'count' => $foods->count(),
        'data' => $foods,
    ]);
}

}
