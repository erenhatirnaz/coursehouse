<?php

namespace App;

use App\LessonPeriod;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'age_range_min', 'age_range_max', 'quota',
        'lesson_period',
    ];

    protected $casts = [
        'lesson_period' => LessonPeriod::class,
    ];

    public function announcements()
    {
        return $this->hasMany('App\Announcement')->orderBy('created_at', 'desc');
    }

    public function students()
    {
        return $this->belongsToMany('App\Student', 'class_room_student', 'class_room_id', 'user_id');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }
}
