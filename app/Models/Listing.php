<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $table="listing";
    protected $fillable = [];

     // Define the relationship
     public function user()
     {
         return $this->belongsTo(User::class);
     }

    public function photos()
    {
        return $this->hasMany(Gallery::class);  // A business can have many photos
    }
}
