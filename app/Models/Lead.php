<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [];
    // Define relationship with client
    public function client()
    {
        return $this->belongsTo(Clients::class);
    }
}
