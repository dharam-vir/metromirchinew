<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table="gallery";
    protected $fillable = [];

    public function listing()
    {
        return $this->belongsTo(Listing::class);  // A photo belongs to a business
    }
}
