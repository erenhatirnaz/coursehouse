<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\RepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\CourseCategoryRepositoryInterface;
use App\Repositories\CourseCategoryRepository;
use App\Repositories\AnnouncementRepositoryInterface;
use App\Repositories\AnnouncementRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, BaseRepository::class);
        $this->app->bind(CourseCategoryRepositoryInterface::class, CourseCategoryRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(AnnouncementRepositoryInterface::class, AnnouncementRepository::class);
    }
}
