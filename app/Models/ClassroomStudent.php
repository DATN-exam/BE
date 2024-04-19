<?php

namespace App\Models;

use App\Enums\Classroom\ClassroomStudentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'classroom_id',
        'status'
    ];

    protected $casts = [
        'status' => ClassroomStudentStatus::class,
    ];
}
