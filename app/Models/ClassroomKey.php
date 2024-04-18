<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'status',
        'quantity',
        'expired',
    ];
}
