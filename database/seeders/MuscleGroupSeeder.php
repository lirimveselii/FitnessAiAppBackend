<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;


class MuscleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path("seeders/data/muscle_group.csv");

        if(!File::exists($path)){
               $this->command->error("CSV file not found at: $path");
            return;
        }

        $csv = array_map('str_getcsv', file($path));
        $header = array_map('trim', $csv[0]);
        unset($csv[0]);

        foreach($csv as $row){
            $data = array_combine($header , $row);

            DB::table("muscle_groups")->insert([

                "region"=> $data["region"],
                "muscle_group"=> $data["muscle_group"],	
            ]);
        } 
        $this->command->info("muscle group seeded successfully!");
    }
}
