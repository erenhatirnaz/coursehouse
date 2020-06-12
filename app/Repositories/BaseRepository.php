<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(array $attributes, int $id): ?Model
    {
        $record = $this->show($id);
        $record->update($attributes);
    }

    public function delete(int $id)
    {
        return $this->model->destory($id);
    }

    public function show(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }
}
