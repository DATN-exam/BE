<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Traits\BaseScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, BaseScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'google_id',
        'password',
        'status',
        'role',
        'first_name',
        'last_name',
        'dob',
        'ward_id',
        'address',
        'avatar',
        'description',
        'token_verify',
        'email_forgot_at'
    ];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name
        );
    }

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
        'password' => 'hashed',
        'status' => UserStatus::class,
        'role' => UserRole::class,
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAdmin()
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isTeacher()
    {
        return $this->role === UserRole::TEACHER;
    }

    public function isStudent()
    {
        return $this->role === UserRole::STUDENT;
    }

    public function teacherRegistrations(): HasMany
    {
        return $this->hasMany(TeacherRegistration::class, 'user_id', 'id');
    }

    public function isBocked()
    {
        return $this->status !== UserStatus::ACTIVE;
    }

    protected function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, ClassroomStudent::class, 'student_id', 'classroom_id');
    }

    public function myClassrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'teacher_id', 'id');
    }

    public function classroomStudents()
    {
        return $this->hasMany(ClassroomStudent::class, 'student_id', 'id');
    }

    public function setQuestion()
    {
        return $this->hasMany(SetQuestion::class, 'teacher_id', 'id');
    }

    public function setQuestions()
    {
        return $this->hasMany(SetQuestion::class, 'teacher_id', 'id');
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
