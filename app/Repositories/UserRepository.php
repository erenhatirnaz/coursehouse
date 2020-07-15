<?php

namespace App\Repositories;

use App\User;
use BadMethodCallException;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository contructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function create(array $attributes): Model
    {
        throw new BadMethodCallException(
            "`create` method is disabled for this repository. Instead, use one of them: " .
            "AdminRepository, OrganizerRepository, TeacherRepository, StudentRepository."
        );
    }

    public function update(array $attributes, $id): Model
    {
        throw new BadMethodCallException(
            "`update` method is disabled for this repository. Instead, use one of them: " .
            "AdminRepository, OrganizerRepository, TeacherRepository, StudentRepository."
        );
    }
}
