<?php

namespace App\Repositories;

use App\CourseCategory;
use App\Repositories\CourseCategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CourseCategoryRepository extends BaseRepository implements CourseCategoryRepositoryInterface
{
    /**
     * CourseCategoryRepository contructor.
     *
     * @param CourseCategory $model
     */
    public function __construct(CourseCategory $model)
    {
        parent::__construct($model);
    }
}
