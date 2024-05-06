<?php

namespace App\Models;

use App\Enums\Classroom\ClassroomStatus;
use App\Traits\BaseScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory, BaseScope;

    protected $fillable = [
        'name',
        'teacher_id',
        'status',
        'description',
        'avatar'
    ];

    protected $casts = [
        'status' => ClassroomStatus::class,
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, ClassroomStudent::class, 'classroom_id', 'student_id');
    }

    public function classroomStudents(): HasMany
    {
        return $this->hasMany(ClassroomStudent::class, 'classroom_id', 'id');
    }
}
