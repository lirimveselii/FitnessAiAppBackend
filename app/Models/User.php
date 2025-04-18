<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\MealPlan;
use App\Models\UserProgress;
use App\Models\UserPreference;
use App\Models\AiRecommendation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'gender',
        'height_cm',
        'weight_kg',
        'user_type',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function aiRecommendation()
    {
        return $this->hasMany(AiRecommendation::class);
    }
    public function workouts()
    {
        return $this->hasMany(AiRecommendation::class);
    }
    public function mealPlans()
    {
        return $this->hasMany(MealPlan::class);
    }
    public function userPreferences()
    {
        return $this->hasMany(UserPreference::class);
    }
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

}
