<?php

declare(strict_types=1);


namespace App\Repositories\Contracts;

/**
 * Interface BaseRepositoryInterface.
 */
interface BaseRepository
{
    public function create(array $attributes): mixed;

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;

    public function findOrFail(int $id): mixed;

    public function delete(int $id): mixed;

    public function first(): mixed;

    public function last(): mixed;

    public function insert(array $attributes): mixed;

    public function get(): mixed;

    public function updateAndGetUpdatedData(string $field, int $id, array $attributes): object|null;

    public function deleteTableAllData(): int;

    public function firstOrFail(string $field, int $value): object|null;
}
