<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

  	protected $table = 'documents';

   /**
    *  Associate User for document.
    */
    public function user() {
        return $this->belongsTo(User::class);
    }

   /**
    *  Associate User for document.
    */
    public function history() {
        return $this->hasMany(DocHistory::class);
    }



}
