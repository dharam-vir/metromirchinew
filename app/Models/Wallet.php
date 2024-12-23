<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function addMoney($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    public function spendMoney($amount)
    {
        // Check if the wallet has enough balance
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            $this->save();
            return true;
        } else {
            return false;  // Insufficient balance
        }
    }
}
