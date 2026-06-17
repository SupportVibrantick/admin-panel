<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class MlmUser extends Model
{
    protected $table = 'mlm_users';
    
    protected $fillable = [
        'user_name', 'track_id', 'first_name', 'last_name',
        'email', 'phone', 'password', 'sponsor_id', 'position_in_sponsor_leg',
        'membership_type', 'desired_membership_type', 'current_package_id',
        'is_active', 'is_verified', 'is_deleted', 'is_defaulter', 'is_payout_active',
        'verification_token', 'verification_expires','commission_percentage'
    ];

    protected $hidden = ['password', 'verification_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'is_deleted' => 'boolean',
        'is_defaulter' => 'boolean',
        'is_payout_active' => 'boolean',
        'verification_expires' => 'datetime',
    ];

    // 🔗 Self-referential: Sponsor (within mlm_users)
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(MlmUser::class, 'sponsor_id');
    }
    public function sponsorId(): BelongsTo
    {
        return $this->belongsTo(MlmUser::class, 'sponsor_id');
    }

    // 🔗 Direct Downlines (within mlm_users)
    public function directDownlines(): HasMany
    {
        return $this->hasMany(MlmUser::class, 'sponsor_id');
    }

    // 🔗 Left Leg Child (direct)
    public function leftLegChild(): HasOne
    {
        return $this->hasOne(MlmUser::class, 'sponsor_id')->where('position_in_sponsor_leg', 'left');
    }

    // 🔗 Right Leg Child (direct)
    public function rightLegChild(): HasOne
    {
        return $this->hasOne(MlmUser::class, 'sponsor_id')->where('position_in_sponsor_leg', 'right');
    }

    // 🔗 Tree Node (1:1 relation with mlm_trees)
    public function tree(): HasOne
    {
        return $this->hasOne(MLMTree::class, 'mlm_user_id');
    }

    // 🔗 Spilling Preference
    public function spillingPreference(): HasOne
    {
        return $this->hasOne(SpillingPreference::class, 'mlm_user_id');
    }

    // 🔗 Package
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'current_package_id');
    }

    // ✅ Helper: Is this user root of MLM tree?
    public function isRoot(): bool
    {
        return is_null($this->sponsor_id) && $this->position_in_sponsor_leg === 'none';
    }

    // ✅ Helper: Full name
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
    public function commissionSetting()
{
    return $this->hasOne(UserCommissionSetting::class, 'mlm_user_id');
}
public function payoutBalance()
{
    return $this->hasOne(\App\Models\PayoutBalance::class, 'mlm_user_id');
}

public function payoutTransactions()
{
    return $this->hasMany(\App\Models\PayoutTransaction::class, 'mlm_user_id');
}
}