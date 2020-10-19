<?php

namespace App\Repositories;

use App\ClassRoom;
use App\Repositories\ClassRoomRepositoryInterface;
use Illuminate\Support\Collection;

class ClassRoomRepository extends BaseRepository implements ClassRoomRepositoryInterface
{
    /**
     * ClassRoomRepository constructor.
     *
     * @param ClassRoom $model
     */
    public function __construct(ClassRoom $model)
    {
        parent::__construct($model);
    }
}
