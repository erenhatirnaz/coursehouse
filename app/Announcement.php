<?php

namespace App;

use App\ClassRoom;
use App\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Announcement extends Model
{
    public $incrementing = false;

    /**
     * All fields are fillable.
     *
     * @var array
     */
    protected $guarded = [];

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

    public function getLinkAttribute()
    {
        return route('announcement.details', ['announcement' => $this->slug]);
    }

    public function getPosterImageAttribute()
    {
        if (Str::startsWith($this->poster_image_path, 'http')) {
            return $this->poster_image_path;
        } else {
            return asset("img/announcements/{$this->poster_image_path}");
        }
    }
}
