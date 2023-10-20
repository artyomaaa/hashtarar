<?php

namespace App\Models;

use Carbon\Carbon;

class PasswordReset extends Model
{
    protected $table = 'password_resets';

    protected $fillable = [
        'email',
        'token',
    ];

    public function isExpired(): bool
    {
        return $this->created_at < Carbon::now()->subHour();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->timestamps = false;
            $model->created_at = Carbon::now();
        });
    }
}
