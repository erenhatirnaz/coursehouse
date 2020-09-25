<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\CourseCategoryRepositoryInterface;
use App\Repositories\AnnouncementRepositoryInterface;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Changes web site's language.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLang(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lang' => ['required', 'in:en,tr'],
        ]);

        if ($validator->fails()) {
            return redirect()->home();
        }

        $request->session()->put('locale', $request->lang);
        return redirect()->back();
    }
}
