<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Contracts\IRoleRepository as RoleRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

final class RoleRepository
    extends BaseRepository
    implements RoleRepositoryContract
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function findByName(string $name): Role|null
    {
        return $this->model->where('name', $name)->firstOrFail();
    }

    public function getRoles(): Collection
    {
        return $this->model->query()->get();
    }

    public function getRoleNames(): array
    {
        return $this->model->pluck('name')->toArray();
    }
}
