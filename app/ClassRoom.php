<?php

namespace App;

use App\Course;
use App\Student;
use App\Announcement;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    /**
     * All fields are fillable.
     *
     * @var array
     */
    protected $guarded = [];

    public function announcements()
    {
        return $this->hasMany(Announcement::class)->orderBy('created_at', 'desc');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_room_student', 'class_room_id', 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getLinkAttribute()
    {
        return route('course.classroom.details', ['course' => $this->course->slug, 'classRoom' => $this->slug]);
    }
}
