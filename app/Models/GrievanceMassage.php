<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrievanceMassage extends Model
{
    protected $fillable = [
        'grivance_id',
        'sender_id',
        'message',
        'attachment',
    ];

    /**
     * NULL sender_id = admin reply.
     */
    public function grievance()
    {
        return $this->belongsTo(Grivance::class, 'grievance_id');
    }

    public function sender()
    {
        return $this->belongsTo(MlmUser::class, 'sender_id');
    }

    public function attachments()
    {
        return $this->hasMany(GrievanceAttachment::class, 'message_id');
    }

    /**
     * True when the message was sent by the admin (sender_id is null).
     */
    public function isAdminReply(): bool
    {
        return is_null($this->sender_id);
    }
}
