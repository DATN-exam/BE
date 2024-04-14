<?php

namespace App\Models;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'description',
        'employee_cofirm_id',
        'reason',
    ];

    protected $casts = [
        'status' => TeacherRegistrationStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function employeeCofirm(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_cofirm_id', 'id');
    }

    public function canCofirm(): bool
    {
        return $this->status === TeacherRegistrationStatus::WAIT;
    }
}
