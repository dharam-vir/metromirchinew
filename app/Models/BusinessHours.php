<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHours extends Model
{
    use HasFactory;
    protected $table="business_hours";
    protected $fillable = [];

      // Relationship with the Listing model
      public function listing()
      {
          return $this->belongsTo(Listing::class);
      }
}
