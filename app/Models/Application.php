<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_type_id',
        'citizen_id',
        'judge_id',
        'mediator_id',
        'number',
        'status',
        'application',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $applicationId = (Application::latest()->first()?->id ?? 0) + 1;
            $number = (string)$applicationId;

            if ($applicationId < 1000) {
                $number = str_repeat("0", 4 - strlen($number)) . $number;
            }

            $model->number = $number;
        });
    }

    public function getAttachmentsPath(): string
    {
        return 'applications/' . $this->id . '/attachments';
    }

    public function getApplicationPath(): string
    {
        return 'applications/' . $this->id . '/application';
    }

    public function getDocumentsPath(): string
    {
        return 'applications/' . $this->id . '/documents';
    }

    public function caseType(): BelongsTo
    {
        return $this->belongsTo(CaseType::class);
    }

    public function mediator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mediator_id');
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ApplicationAttachment::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ApplicationComment::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(ApplicationMediatorResult::class);
    }
}
