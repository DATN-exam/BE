<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'set_question_id',
        'classroom_id',
        'name',
        'note',
        'working_time',
        'is_show_result',
        'start_date',
        'end_date',
        'number_question_hard',
        'number_question_medium',
        'number_question_easy',
    ];

    public function setQuestion()
    {
        return $this->belongsTo(SetQuestion::class, 'set_question_id', 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function examHistories()
    {
        return $this->hasMany(ExamHistory::class, 'exam_id', 'id');
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => checkStatusExam($this->start_date, $this->end_date),
        );
    }
}
