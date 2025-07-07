<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $path = database_path('seeders/data/exercises.csv');

        if (!File::exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = array_map('str_getcsv', file($path));
        $header = array_map('trim', $csv[0]);
        unset($csv[0]);

        foreach ($csv as $row) {
            $data = array_combine($header, $row);

            DB::table('exercises')->insert([
    'name' => $data['name'],
    'category' => $data['category'],
    'muscle_group' => $data['muscle_group'],
    'equipment' => $data['equipment'],
    'description' => $data['description'],
    'video_url' => $data['video_url'],
    'difficulty_level' => $data['difficulty_level'],
    'calories_burned' => (int) $data['calories_burned'],
    'intensity' => $data['intensity'],
    'tags' => $data['tags'],
    'normalized_name' => $data['normalized_name'],
    'source_type' => $data['source_type'],
    'created_by' => empty($data['created_by']) ? null : (int) $data['created_by'],
    'is_public' => filter_var($data['is_public'], FILTER_VALIDATE_BOOLEAN),
    'created_at' => $data['created_at'] ?? now(),
    'updated_at' => $data['updated_at'] ?? now(),
            ]);
        }

        $this->command->info("Exercises seeded successfully!");
    }
}
