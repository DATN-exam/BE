<?php

namespace App\Models;

use App\Enums\ExamHistory\ExamHistoryStatus;
use App\Enums\ExamHistory\ExamHistoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'start_time',
        'submit_time',
        'is_submit',
        'type',
        'total_score',
        'status'
    ];

    protected $casts = [
        'type' => ExamHistoryType::class,
        'status' => ExamHistoryStatus::class,
        'is_submit' => 'boolean',
    ];

    public function examAnswers()
    {
        return $this->hasMany(ExamAnwser::class, 'exam_history_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
}
