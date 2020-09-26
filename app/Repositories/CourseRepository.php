<?php

namespace App\Repositories;

use App\Course;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\CourseRepositoryInterface;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    /**
     * CourseRepository contructor.
     *
     * @param Course $model
     */
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function showBySlug(string $slug): ?Model
    {
        $course = $this->model->firstWhere('slug', $slug);
        if (!$course) {
            throw new ModelNotFoundException("No query results for model [App\Course] '{$slug}'");
        }

        return $course;
    }
}
