<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MediatorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_date',
        'application_type_id',
        'application_cause',
        'user_id',
        'status',
        'is_license',
        'reason',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $mediatorApplicationId = (MediatorApplication::latest()->first()?->id ?? 0) + 1;
            $number = (string)$mediatorApplicationId;

            if ($mediatorApplicationId < 1000) {
                $number = str_repeat("0", 4 - strlen($number)) . $number;
            }

            $model->number = $number;
        });
    }


    public function mediator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function caseTypes(): HasOne
    {
        return $this->hasOne(MediatorApplicationCaseType::class, 'id', 'application_type_id');
    }

    public function getAttachmentsPath(): string
    {
        return 'mediatorApplications/' . $this->id . '/attachments';
    }

    public function getCVPath(): string
    {
        return 'users/' . $this->user_id . '/cvs';
    }

    public function getAvatarPath(): string
    {
        return 'users/' . $this->user_id . '/avatars';
    }

}
