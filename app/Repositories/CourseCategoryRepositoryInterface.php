<?php

namespace App\Repositories;

use App\CourseCategory;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface CourseCategoryRepositoryInterface
{
    public function all(): Collection;

    public function create(array $attributes): Model;

    public function update(array $attributes, int $id): ?Model;

    public function delete($ids);

    public function show(int $id): ?Model;
}
