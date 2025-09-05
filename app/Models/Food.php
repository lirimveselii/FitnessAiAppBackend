<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomRecepie;

class Food extends Model
{
        protected $fillable = [
        'name',
        'brand',
        'serving_amount',
        'serving_unit',
        'calories',
        'protein_g',
        'carbs_g',
        'fat_g',
    ];
    protected $table = 'foods';

       public function recepies()
    {
        return $this->belongsToMany(CustomRecepie::class, 'custom_recepies_food', 'food_id', 'custom_recepies_id')
                    ->withPivot(['servings', 'notes'])
                    ->withTimestamps();
    }
}
