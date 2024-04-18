<?php

namespace App\Models;

use App\Enums\Classroom\ClassroomUserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'classroom_id',
        'status'
    ];

    protected $casts = [
        'status' => ClassroomUserStatus::class,
    ];
}
