<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grivance extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_no',
        'subject',
        'category',
        'priority',
        'status',
        'closed_at',
    ];

    public function user()
    {
        return $this->belongsTo(MlmUser::class, 'user_id');
    }

    public static function generateTicketNo(): string
    {
        $prefix = 'GRV' . now()->format('Ymd');

        $lastTicket = self::whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();

        $sequence = 1;

        if ($lastTicket && preg_match('/(\d{4})$/', $lastTicket->ticket_no, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function messages()
    {
        return $this->hasMany(GrievanceMassage::class, 'grievance_id');
    }
}
