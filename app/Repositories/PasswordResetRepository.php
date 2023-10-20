<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PasswordReset;
use App\Repositories\Contracts\IPasswordResetRepository as PasswordResetRepositoryContract;
use Carbon\Carbon;

final class PasswordResetRepository
    extends BaseRepository
    implements PasswordResetRepositoryContract
{
    public function __construct(PasswordReset $model)
    {
        parent::__construct($model);
    }

    public function create(array $attributes): mixed
    {
        $attributes['created_at'] = Carbon::now();
        return $this->model->create($attributes);
    }

    public function findBy(string $email, string $token): PasswordReset|null
    {
        return $this->model->where('email', $email)->where('token', $token)->first();
    }

    public function findByEmail(string $email): PasswordReset|null
    {
        return $this->model->where('email', $email)->first();
    }

    public function deleteByEmail(string $email)
    {
        return $this->model->where('email', $email)->delete();
    }
}
