<?php

namespace App;

use App\PaymentPeriod;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'id', 'slug', 'title', 'description', 'poster_image_path', 'starts_at', 'ends_at',
        'quota', 'price', 'payment_period', 'is_featured',
    ];

    protected $casts = [
        'id' => 'uuid',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'payment_period' => PaymentPeriod::class,
    ];
}
