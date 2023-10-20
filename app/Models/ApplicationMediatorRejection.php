<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationMediatorRejection extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'mediator_id',
        'reason',
        'authorized_reject',
    ];
}
