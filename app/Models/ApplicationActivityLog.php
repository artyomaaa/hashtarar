<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
      'application_id',
      'user_id',
      'data',
      'type',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
