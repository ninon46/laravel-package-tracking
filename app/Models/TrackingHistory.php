<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'package_id',
        'status',
        'description',
        'location',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
