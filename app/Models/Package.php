<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'tracking_number',
        'qr_code',
        'user_id',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
        'delivery_address',
        'city',
        'postal_code',
        'status',
        'shipped_at',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trackingHistories(): HasMany
    {
        return $this->hasMany(TrackingHistory::class);
    }

    public function generateTrackingNumber()
    {
        $prefix = 'PKG';
        $timestamp = now()->format('YmdHis');
        $random = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $timestamp . $random;
    }
}
