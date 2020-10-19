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
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\StudentRepositoryInterface;
use App\Repositories\StudentRepository;
use App\Repositories\AdminRepositoryInterface;
use App\Repositories\AdminRepository;
use App\Repositories\OrganizerRepositoryInterface;
use App\Repositories\OrganizerRepository;
use App\Repositories\TeacherRepositoryInterface;
use App\Repositories\TeacherRepository;
use App\Repositories\ClassRoomRepositoryInterface;
use App\Repositories\ClassRoomRepository;

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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(OrganizerRepositoryInterface::class, OrganizerRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(ClassRoomRepositoryInterface::class, ClassRoomRepository::class);
    }
}
