<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundSummary extends Model
{
    use HasFactory;

    protected $table = 'fund_summaries';

    protected $fillable = [
        'user_id',
        'username',
        'transaction_date',
        'type',
        'particular',
        'remark',
        'credit',
        'debit',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'credit' => 'decimal:2',
        'debit' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(MlmUser::class, 'user_id');
    }
}