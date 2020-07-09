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

    public function allWithRelations(array $relations): Collection
    {
        return $this->model->with($relations)->get();
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(array $attributes, $id): ?Model
    {
        $record = $this->show($id);
        $record->update($attributes);

        return $record;
    }

    public function delete($ids)
    {
        return ($this->model->destroy($ids) > 0) ? true : false;
    }

    public function show($id): ?Model
    {
        return $this->model->findOrFail($id);
    }
}
