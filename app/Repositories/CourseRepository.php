<?php

namespace App\Repositories;

use App\Course;
use Illuminate\Support\Collection;
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

    public function allWithRelations(array $relations): Collection
    {
        return $this->model->with($relations)->get();
    }
}
