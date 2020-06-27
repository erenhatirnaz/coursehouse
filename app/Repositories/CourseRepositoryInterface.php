<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface CourseRepositoryInterface
{
    public function all(): Collection;

    public function allWithRelations(array $relations): Collection;

    public function create(array $attributes): Model;

    public function update(array $attributes, int $id): ?Model;

    public function delete(int $id);

    public function show(int $id): ?Model;
}
