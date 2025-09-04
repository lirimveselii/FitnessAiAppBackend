<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $path = database_path('seeders/data/sample_foods.csv');

        if (!File::exists($path)) {
            $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = array_map('str_getcsv', file($path));
        $header = array_map('trim', $csv[0]);
        unset($csv[0]);

        foreach ($csv as $row) {
            $data = array_combine($header, $row);

     DB::table('foods')->insert([
    'name' => $data['name'],
    'category' => $data['category'] ?? null,
    'calories' => isset($data['calories']) ? (int) $data['calories'] : null,
    'protein' => isset($data['protein']) ? (float) $data['protein'] : null,
    'carbs' => isset($data['carbs']) ? (float) $data['carbs'] : null,
    'fat' => isset($data['fat']) ? (float) $data['fat'] : null,
    'fiber' => isset($data['fiber']) ? (float) $data['fiber'] : null,
    'sugar' => isset($data['sugar']) ? (float) $data['sugar'] : null,
    'serving_size' => $data['serving_size'] ?? '100g',
    'unit_weight_g' => isset($data['unit_weight_g']) ? (float) $data['unit_weight_g'] : 100.0,
    'brand' => $data['brand'] ?? null,
    'created_at' => $data['created_at'] ?? now(),
    'updated_at' => $data['updated_at'] ?? now(),
]);
        }

        $this->command->info("Exercises seeded successfully!");
    }
}
