<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MlmUserDetail extends Model
{
    protected $table = 'mlm_users_details';

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'father_name',
        'mother_name',
        'address_line_1',
        'address_line_2',
        'city',
        'district',
        'state',
        'country',
        'pincode',
        'nominee_name',
        'nominee_relation',
        'profile_image',
    ];

    public function user()
    {
        return $this->belongsTo(MlmUser::class, 'user_id');
    }
}
