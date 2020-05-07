<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'image_path',
    ];

    public function category()
    {
        return $this->belongsTo('App\CourseCategory', 'course_category_id', 'id');
    }

    public function teachers()
    {
        return $this->belongsToMany('App\Teacher', 'course_teacher', 'course_id', 'user_id');
    }

    public function organizers()
    {
        return $this->belongsToMany('App\Organizer', 'course_organizer', 'course_id', 'user_id');
    }

    public function classRooms()
    {
        return $this->hasMany('App\ClassRoom');
    }
}
