<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomRecepie;
use Illuminate\Support\Facades\Auth;

class CustomRecepieController extends Controller
{
    public function store(Request $request)
{
    $data = $request->validate([
        'name'          => ['required', 'string', 'max:255'],
        'servings'      => ['nullable', 'integer', 'min:1'],
        'prep_minutes'  => ['nullable', 'integer', 'min:0'],
        'cook_minutes'  => ['nullable', 'integer', 'min:0'],
        'foods'         => ['required', 'array', 'min:1'],
        'foods.*.food_id'  => ['required', 'exists:foods,id'],
        'foods.*.servings' => ['required', 'numeric', 'gt:0', 'max:1000'],
        'foods.*.notes'    => ['nullable', 'string', 'max:255'],
    ]);

    $recipe = CustomRecepie::create([
        'user_id'      => Auth::id() ?? 1, // replace with actual user ID
        'name'         => $data['name'],
        'servings'     => $data['servings'] ?? 1,
        'prep_minutes' => $data['prep_minutes'] ?? 0,
        'cook_minutes' => $data['cook_minutes'] ?? 0,
    ]);

    // attach foods on pivot
    $attach = [];
    foreach ($data['foods'] as $f) {
        $attach[$f['food_id']] = [
            'servings' => $f['servings'],
            'notes'    => $f['notes'] ?? null,
        ];
    }
    $recipe->foods()->attach($attach);

    return response()->json(['id' => $recipe->id], 201);
}


public function show($id)
{
    $recipe = CustomRecepie::with([
        'foods' => function ($q) {
            $q->select('foods.id','foods.name','brand','serving_size','unit_weight_g',
                       'calories','protein','carbs','fat');
        }
    ])->findOrFail($id);

    // Compute macro totals
    $totals = ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0];

    $foods = $recipe->foods->map(function ($food) use (&$totals) {
        $s = (float) ($food->pivot->servings ?? 1);

        $foodTotals = [
            'calories'  => round((float)$food->calories   * $s, 2),
            'protein'   => round((float)$food->protein_g * $s, 2),
            'carbs'     => round((float)$food->carbs_g   * $s, 2),
            'fat'       => round((float)$food->fat_g     * $s, 2),
        ];

        // accumulate overall
        foreach ($foodTotals as $k => $v) $totals[$k] += $v;

        return [
            'id'            => $food->id,
            'name'          => $food->name,
            'brand'         => $food->brand,
            'serving_base'  => $food->serving_amount.' '.$food->serving_unit, // e.g. "100 g"
            'pivot'         => [
                'servings' => (float)$food->pivot->servings,
                'notes'    => $food->pivot->notes,
            ],
            'totals'        => $foodTotals, 
        ];
    });

    $servings = max(1, (int) ($recipe->servings ?? 1));
    $perServing = [
        'calories'  => round($totals['calories']  / $servings, 2),
        'protein'   => round($totals['protein']   / $servings, 2),
        'carbs'     => round($totals['carbs']     / $servings, 2),
        'fat'       => round($totals['fat']       / $servings, 2),
    ];

    return response()->json([
        'id'            => $recipe->id,
        'name'          => $recipe->name,
        'servings'      => $servings,
        'prep_minutes'  => (int)($recipe->prep_minutes ?? 0),
        'cook_minutes'  => (int)($recipe->cook_minutes ?? 0),
        'foods'         => $foods,
        'totals'        => array_map(fn($v)=>round($v,2), $totals),  // whole recipe
        'per_serving'   => $perServing,                               // optional
    ]);
}
}
