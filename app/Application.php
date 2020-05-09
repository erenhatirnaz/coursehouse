<?php

namespace App;

use App\ApplicationStatus;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id', 'status',
    ];

    protected $casts = [
        'id' => 'uuid',
        'announcement_id' => 'uuid',
        'status' => ApplicationStatus::class,
    ];

    public function announcement()
    {
        return $this->belongsTo('App\Announcement');
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }
}
