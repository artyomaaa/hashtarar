<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'email',
        'password',
        'role_id',
        'ssn',
        'phone',
        'other_means',
        'birthdate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getMediatorAttachmentsPath(): string
    {
        return 'mediators/' . $this->id . '/attachments';
    }

    public function getAvatarsPath(): string
    {
        return 'users/' . $this->id . '/avatars';
    }

    public function getCvsPath(): string
    {
        return 'users/' . $this->id . '/cvs';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function isEmployeeOrAdmin(): bool
    {
        return in_array($this->role?->name, [UserRoles::ADMIN->value, UserRoles::EMPLOYEE->value]);
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === UserRoles::ADMIN->value;
    }

    public function isEmployee(): bool
    {
        return $this->role?->name === UserRoles::EMPLOYEE->value;
    }

    public function isMediator(): bool
    {
        return $this->role?->name === UserRoles::MEDIATOR->value;
    }

    public function isCitizen(): bool
    {
        return $this->role?->name === UserRoles::CITIZEN->value;
    }

    public function isJudge(): bool
    {
        return $this->role?->name === UserRoles::JUDGE->value;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function citizenCompany(): HasOne
    {
        return $this->hasOne(CitizenCompany::class);
    }

    public function mediatorDetails(): HasOne
    {
        return $this->hasOne(MediatorDetails::class);
    }

    public function judgeDetails(): HasOne
    {
        return $this->hasOne(JudgeDetails::class);
    }

    public function mediatorExams(): HasOne
    {
        return $this->hasOne(MediatorExam::class);
    }


    public function mediatorApplication(): HasMany
    {
        return $this->hasMany(MediatorApplication::class, 'id', 'user_id');
    }
    public function mediatorCourse(): HasMany
    {
        return $this->hasMany(MediatorCourse::class, 'mediator_id', 'id');
    }
}
