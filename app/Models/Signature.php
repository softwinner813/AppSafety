<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

  	protected $table = 'signatures';
    
   /**
    *  Associate User for membership.
    */
    public function user() {
        return $this->belongsTo(User::class);
    }

}
