<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\CourseCategoryRepositoryInterface;

class CourseController extends Controller
{

    /**
     * @var CourseCategoryRepository
     */
    private $courseCategoryRepository;

    /**
     * @var CourseRepository
     */
    private $courseRepository;

    public function __construct(
        CourseCategoryRepositoryInterface $courseCategoryRepository,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->courseCategoryRepository = $courseCategoryRepository;
        $this->courseRepository = $courseRepository;
    }

    public function index(Request $req)
    {
        $this->validateFilters($req);

        $courses = $this->courseRepository->allWithRelations(['category', 'teachers']);
        $categories = $courses->pluck('category');
        $teachers = $courses->pluck('teachers')->collapse()->unique('id')->sortByDesc('courses_count');
        $totalCourseCount = $courses->count();

        $filters = collect();

        if ($req->input()) {
            // apply category filter
            $categorySlug = $req->input('category') ?: "all";
            if ($categorySlug != "all") {
                $courses = $courses->filter(function ($course) use ($categorySlug) {
                    return $course->category->slug == $categorySlug;
                })->unique();

                $filters->put('category', $courses[0]->category->name);
            }

            // apply teacher(s) filter
            $teacherIDs = $req->input('teacher.*') ?: "all";
            if ($teacherIDs != "all") {
                $selectedTeachers = $teachers->whereIn('id', $teacherIDs);

                $coursesFilteredByTeacher = $courses->filter(function ($course) use ($selectedTeachers) {
                    $courseTeachers = $course->teachers->pluck('id');
                    return (!empty($selectedTeachers->whereIn('id', $courseTeachers)->toArray()));
                })->unique();

                $courses = $coursesFilteredByTeacher;
                $filters->put('teachers', $selectedTeachers);
            }

            // apply status filter
            $status = $req->input('status') ?: "all";
            if ($status != "all") {
                $courses = $courses->filter(function ($course) use ($status) {
                    return $course->status == $status;
                });
                $filters->put('status', $status);
            }
        }

        return view('course.explorer', [
            "total_course_count" => $totalCourseCount,
            "filters" => $filters,
            "categories" => $categories,
            "teachers" => $teachers,
            "courses" => $courses,
        ]);
    }

    public function show(string $slug)
    {
        $course = $this->courseRepository->getBySlug($slug, ['teachers']);

        return view('course.details', compact('course'));
    }

    private function validateFilters(Request $req)
    {
        $rules = [
            'category' => ['in:all'],
            'teacher.*' => ['in:all'],
            'status' => ['string', 'in:active,passive,all'],
        ];
        if ($req->input('category') != "all") {
            $rules["category"] = ["string", "exists:App\CourseCategory,slug"];
        }
        if ($req->input('teacher') != "all") {
            $rules["teacher.*"] = ["exists:App\Teacher,id"];
        }
        Validator::make($req->all(), $rules)->validate();
    }
}
