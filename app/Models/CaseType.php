<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseType extends Model
{
    use HasFactory;

    const ACTIVE = 1;
    const IN_ACTIVE = 0;

    protected $fillable = [
        'name',
        'group_id',
        'status',
    ];
}
