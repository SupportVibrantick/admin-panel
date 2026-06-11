<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminBankDetail extends Model
{
    protected $fillable = [
        'mode_name',
        'address',
        'account_no',
        'bank_name',
        'ifsc_code',
        'image',
        'is_active',
    ];
}
