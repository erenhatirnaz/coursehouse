<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    protected $fillable = [
        'slug', 'name'
    ];

    protected $withCount = [ 'courses' ];

    public function courses()
    {
        return $this->hasMany('App\Course');
    }
}
