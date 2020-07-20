<?php

namespace App\Repositories;

use App\Organizer;
use App\Repositories\OrganizerRepositoryInterface;

class OrganizerRepository extends BaseRepository implements OrganizerRepositoryInterface
{
    /**
     * OrganizerRepository constructor.
     *
     * @param Organizer $model
     */
    public function __construct(Organizer $model)
    {
        parent::__construct($model);
    }
}
