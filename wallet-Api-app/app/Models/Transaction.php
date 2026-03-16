<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['amount','type','wallet_id','description','recevoir_wallet_id','balance_after','sender_wallet_id'];
    
    public function wallets()
    {
        return $this->belongsTo(Wallet::class);
    }

}
