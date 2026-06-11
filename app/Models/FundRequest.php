<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundRequest extends Model
{
    use HasFactory;

    protected $table = 'fund_requests';

    protected $fillable = [
        'user_id',
        'username',
        'bank_detail_id',
        'payment_mode',
        'amount',
        'remark',
        'mode_of_payment',
        'deposit_bank',
        'transaction_no',
        'deposit_date',
        'hash_code_image',
        'status',
        'admin_remark',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deposit_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(MlmUser::class, 'user_id');
    }

    public function bankDetail()
    {
        return $this->belongsTo(AdminBankDetail::class, 'bank_detail_id');
    }
}