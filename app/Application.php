<?php

namespace App;

use App\ApplicationStatus;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'id', 'status',
    ];

    protected $casts = [
        'id' => 'uuid',
        'announcement_id' => 'uuid',
        'status' => ApplicationStatus::class,
    ];
}
