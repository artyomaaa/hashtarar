<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MediatorDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'had_license_before',
        'status',
        'cv',
        'avatar',
        'mediator_company_id',
        'mediator_specialization',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mediatorExams(): HasOne
    {
        return $this->hasOne(MediatorExam::class, 'user_id','user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'mediator_id', 'user_id');
    }

    public function rejections(): HasMany
    {
        return $this->hasMany(ApplicationMediatorRejection::class, 'mediator_id', 'user_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(ApplicationMediatorResult::class, 'mediator_id', 'user_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MediatorAttachment::class, 'mediator_id', 'user_id');
    }

    public function company(): HasOne
    {
        return $this->hasOne(MediatorCompany::class, 'id', 'mediator_company_id');
    }
}
