<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\CourseCategoryRepositoryInterface;
use App\Repositories\AnnouncementRepositoryInterface;

class HomeController extends Controller
{
    /**
     * @var CourseRepository
     */
    private $courses;

    /**
     * @var CourseCategoryRepository
     */
    private $courseCategories;

    /**
     * @var AnnouncementRepository
     */
    private $announcements;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        CourseCategoryRepositoryInterface $courseCategoryRepository,
        AnnouncementRepositoryInterface $announcementRepository
    ) {
        $this->courses = $courseRepository;
        $this->courseCategories = $courseCategoryRepository;
        $this->announcements = $announcementRepository;
    }

    public function index()
    {
        return view('home', [
            'course_categories' => $this->courseCategories->all(),
            'featured_announcements' => $this->announcements->featured(15),
            'announcements' => $this->announcements->allWithRelations(['classRoom']),
            'courses' => $this->courses->allWithRelations(['category', 'teachers']),
        ]);
    }
}
