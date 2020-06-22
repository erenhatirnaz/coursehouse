<?php

namespace App\Repositories;

use App\Announcement;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface AnnouncementRepositoryInterface
{
    public function all(): Collection;

    public function create(array $attributes): Model;

    public function update(array $attributes, int $id): ?Model;

    public function delete(int $id);

    public function show(int $id): ?Model;

    public function featured(int $limit): Collection;
}
