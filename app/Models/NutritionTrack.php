<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionTrack extends Model
{
    use HasFactory;

  	protected $table = 'nutrition_tracks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'id', 'user_id', 'breakfast', 'lunch', 'dinner', 'snacks','sessert', 'fluids',
    // ];
}


