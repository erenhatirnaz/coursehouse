<?php

namespace App;

use App\Course;
use Illuminate\Database\Eloquent\Model;

class Organizer extends User
{
    protected $table = "users";

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->roles()->attach(Roles::ORGANIZER);
        });

        static::addGlobalScope(function ($query) {
            $query->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_user.role_id', Roles::ORGANIZER);
        });
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_organizer', 'user_id', 'course_id');
    }
}
