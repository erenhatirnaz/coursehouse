<?php

namespace App;

use App\Teacher;
use App\Organizer;
use App\ClassRoom;
use App\CourseCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'image_path',
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id', 'id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'course_teacher', 'course_id', 'user_id');
    }

    public function organizers()
    {
        return $this->belongsToMany(Organizer::class, 'course_organizer', 'course_id', 'user_id');
    }

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function getLinkAttribute()
    {
        return route('course.details', ['course' => $this->slug]);
    }

    public function getImageAttribute()
    {
        if (Str::startsWith($this->image_path, 'http')) {
            return $this->image_path;
        } else {
            return asset("img/courses/{$this->image_path}");
        }
    }
}
