<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepository as BaseRepositoryContract;

abstract class BaseRepository implements BaseRepositoryContract
{
    protected $model;

    /** BaseRepository constructor. */
    public function __construct(mixed $model = null)
    {
        $this->model = $model;
    }

    public function create(array $attributes): mixed
    {
        return $this->model->create($attributes);
    }

    public function find(int $id): mixed
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    public function delete(int $id): mixed
    {
        return $this->model->find($id)->delete();
    }

    public function update(int $id, array $attributes): mixed
    {
        return $this->model->find($id)->update($attributes);
    }

    public function first(): mixed
    {
        return $this->model->first();
    }

    public function last(): mixed
    {
        return $this->model->orderByDesc('id')->first();
    }

    public function insert(array $attributes): mixed
    {
        return $this->model->insert($attributes);
    }

    public function get(): mixed
    {
        return $this->model->get();
    }

    public function updateAndGetUpdatedData(string $field, int $id, array $attributes): object|null
    {
        return tap($this->model::where($field, $id))->update($attributes)->first();
    }

    public function deleteTableAllData(): int
    {
        return $this->model->getQuery()->delete();
    }

    public function firstOrFail(string $field, int $value): object|null
    {
        return $this->model->where($field, $value)->firstOrFail();
    }

}
