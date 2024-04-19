<?php

namespace App\Models;

use App\Enums\Classroom\ClassroomKeyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'classroom_id',
        'status',
        'quantity',
        'remaining',
        'expired',
    ];

    protected $casts = [
        'status' => ClassroomKeyStatus::class,
    ];
}
