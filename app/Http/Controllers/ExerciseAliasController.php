<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\ExerciseAlias;


class ExerciseAliasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $exercises = Exercise::all();

        return view("create-alias" ,compact('exercises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           $request->validate([
        'alias' => 'required|string|max:255',
        'exercise_id' => 'required|exists:exercises,id',
    ]);

    $normalized = strtolower(preg_replace('/[^a-z0-9]+/', '_', $request->alias));

    ExerciseAlias::create([
        'exercise_id' => $request->exercise_id,
        'alias' => $request->alias,
        'normalized_name' => $normalized,
    ]);

    return redirect()->back()->with('success', 'Alias added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
