<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface AnnouncementRepositoryInterface
{
    public function all(): Collection;

    public function allWithRelations(array $relations): Collection;

    public function create(array $attributes): Model;

    public function update(array $attributes, $id): ?Model;

    public function delete($ids);

    public function show($id): ?Model;

    public function featured(int $limit = 10): Collection;
}
