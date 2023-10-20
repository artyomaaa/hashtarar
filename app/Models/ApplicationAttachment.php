<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'name',
        'path',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
