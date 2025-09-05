<?php

namespace App\Models;
use App\Models\Food;

use Illuminate\Database\Eloquent\Model;

class CustomRecepie extends Model
{

        protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'servings',
        'prep_minutes',
        'cook_minutes',
        'is_public',
        'cached_calories',
        'cached_protein_g',
        'cached_carbs_g',
        'cached_fat_g',
    ];
    public function foods()
    {
        return $this->belongsToMany(Food::class, 'custom_recepies_food', 'custom_recepies_id', 'food_id')
                    ->withPivot(['servings', 'notes'])
                    ->withTimestamps();
    }
}
