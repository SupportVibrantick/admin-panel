<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $fillable = [
        'user_id',
        'pan_number',
        'aadhaar_number',
        'pan_image',
        'aadhaar_front_image',
        'aadhaar_back_image',
        'bank_document_image',
        'status',
        'reject_reason'
    ];


    public function user()
    {
        return $this->belongsTo(MlmUser::class, 'user_id');
    }
}
