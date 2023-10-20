<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationMeetingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'type',
        'date',
        'address',
        'information',
        'planning',
    ];

    public function recordings(): HasMany
    {
        return $this->hasMany(ApplicationMeetingRecording::class, 'meeting_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function getAttachmentsPath(): string
    {
        return 'applications/' . $this->application_id . '/meetings/' . $this->id . '/recordings';
    }
}
