<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{
    use HasFactory;

    protected $table = 'fund_transfers';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'sender_username',
        'receiver_username',
        'amount',
        'remark',
        'transaction_password',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function sender()
    {
        return $this->belongsTo(MlmUser::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(MlmUser::class, 'receiver_id');
    }
}