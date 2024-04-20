<?php

namespace App\Models;

use App\Enums\Classroom\ClassroomStudentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'classroom_id',
        'status',
        'type_join'
    ];

    protected $casts = [
        'status' => ClassroomStudentStatus::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function keys(): HasMany
    {
        return $this->hasMany(ClassroomKey::class, 'classroom_id', 'id');
    }
}
