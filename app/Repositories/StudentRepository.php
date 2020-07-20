<?php

namespace App\Repositories;

use App\Student;
use App\Repositories\StudentRepositoryInterface;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    /**
     * StudentRepository constructor.
     *
     * @param Student $model
     */
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }
}
