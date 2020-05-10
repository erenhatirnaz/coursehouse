<?php

namespace App;

use App\Student;
use App\Announcement;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id', 'status',
    ];

    protected $casts = [
        'id' => 'string',
        'announcement_id' => 'string',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
