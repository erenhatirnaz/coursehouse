<?php

namespace App;

use App\ClassRoom;
use App\Application;
use Illuminate\Database\Eloquent\Model;

class Student extends User
{
    protected $table = "users";

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->roles()->attach(Roles::STUDENT);
        });

        static::addGlobalScope(function ($query) {
            $query->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_user.role_id', Roles::STUDENT);
        });
    }

    public function classRooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_student', 'user_id', 'class_room_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class)->orderBy('created_at', 'desc');
    }
}
