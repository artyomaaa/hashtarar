<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediatorApplicationCaseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
