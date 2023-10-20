<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediatorCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'status',
    ];
}
