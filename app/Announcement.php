<?php

namespace App;

use App\ClassRoom;
use App\Application;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id', 'slug', 'title', 'description', 'poster_image_path', 'starts_at', 'ends_at',
        'quota', 'price', 'payment_period', 'is_featured',
    ];

    protected $casts = [
        'id' => 'string',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', '=', 1)->orderBy('updated_at', 'desc');
    }
}
