<?php

namespace App\Repositories;

use App\ClassRoom;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ClassRoomRepositoryInterface
{
    public function all(): Collection;

    public function allWithRelations(array $relations): Collection;

    public function create(array $attributes): Model;

    public function update(array $attributes, $id): ?Model;

    public function delete($ids);

    public function show($id): ?Model;
}
