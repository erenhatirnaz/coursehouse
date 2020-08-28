<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use App\Repositories\CourseRepositoryInterface;
use Illuminate\Support\Facades\Cache;

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
        $search_placeholder = Cache::remember('search_placeholder', now()->addHour(), function () {
            $courses = $this->courses->all();
            if ($courses->count() > 0) {
                return $courses->random()->name;
            } else {
                return __('app.search_placeholder');
            }
        });

        $view->with('search_placeholder', $search_placeholder);

        $newLanguage = (Session::get('locale') == 'en') ? "tr" : "en";
        $view->with('newLanguage', $newLanguage);
    }
}
