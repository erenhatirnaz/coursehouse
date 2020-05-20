<?php

namespace App\Http\View\Composers;

use App\Course;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class LayoutComposer
{
    /**
     * @var Course
     */
    protected $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function compose(View $view)
    {
        $random_course = "";

        if ($this->course->count() > 0) {
            $random_course = $this->course->all()->random();
        }

        $search_placeholder = ($random_course)
                            ? $random_course['name']
                            : __('app.search_placeholder');
        $view->with('search_placeholder', $search_placeholder);
    }
}
