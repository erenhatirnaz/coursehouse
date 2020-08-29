<?php

namespace App;

use App\Course;
use Illuminate\Database\Eloquent\Model;

class Teacher extends User
{
    protected $table = "users";

    protected $withCount = [ 'courses' ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->roles()->attach(Roles::TEACHER);
        });

        static::addGlobalScope(function ($query) {
            $query->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_user.role_id', Roles::TEACHER);
        });
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_teacher', 'user_id', 'course_id');
    }
}
