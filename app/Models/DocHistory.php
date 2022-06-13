<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocHistory extends Model
{
    use HasFactory;

  	protected $table = 'doc_histories';

   /**
    *  Associate User for document.
    */
    public function user() {
        return $this->belongsTo(User::class);
    }


}
