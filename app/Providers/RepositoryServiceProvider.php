<?php

namespace App\Providers;

use App\Repositories\RepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\CourseRepository;
use Illuminate\Support\ServiceProvider;

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
    }
}
