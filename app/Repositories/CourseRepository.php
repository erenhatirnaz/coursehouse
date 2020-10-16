<?php

namespace App\Repositories;

use App\Course;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

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

    public function getBySlug(string $slug, array $withCount = []): ?Model
    {
        try {
            $course = $this->model->withCount($withCount)->firstWhere('slug', $slug);
        } catch (\BadMethodCallException $ex) {
            $relationName = substr(explode('::', $ex->getMessage())[1], 0, -2);
            throw new RelationNotFoundException(
                "Call to undefined relationship [{$relationName}] on model [App\Course]."
            );
        }
        if (!$course) {
            throw new ModelNotFoundException("No query results for model [App\Course] '{$slug}'");
        }

        return $course;
    }
}
