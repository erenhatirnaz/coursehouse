<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use App\Repositories\CourseRepositoryInterface;

class LayoutComposer
{
    /**
     * @var Course
     */
    protected $courses;

    public function __construct(CourseRepositoryInterface $course)
    {
        $this->courses = $course;
    }

    public function compose(View $view)
    {
        $search_placeholder = __('app.search_placeholder');

        $courses = $this->courses->all();
        if ($courses->count() > 0) {
            $search_placeholder = $courses->random()['name'];
        }

        $view->with('search_placeholder', $search_placeholder);
    }
}
