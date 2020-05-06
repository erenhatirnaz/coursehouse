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
}
