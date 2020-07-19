<?php

namespace App\Repositories;

use App\Teacher;
use App\Repositories\TeacherRepositoryInterface;

class TeacherRepository extends BaseRepository implements TeacherRepositoryInterface
{
    /**
     * TeacherRepository constructor.
     *
     * @param Teacher $model
     */
    public function __construct(Teacher $model)
    {
        parent::__construct($model);
    }
}
