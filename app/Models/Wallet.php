<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['balance','type','currency','user_id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
