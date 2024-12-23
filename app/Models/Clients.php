<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    
    use HasFactory;
    protected $fillable = ['name', 'email', 'primary_number','secondary_number', 'address','zipcode','city '];
    // Define relationship with leads
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
