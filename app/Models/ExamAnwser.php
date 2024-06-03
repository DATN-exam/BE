<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnwser extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_history_id',
        'question_id',
        'answer_id',
        'answer_text',
        'score',
        'is_correct',
    ];

    public function examHistory()
    {
        return $this->belongsTo(ExamHistory::class,'exam_history_id','id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class,'question_id','id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class,'answer_id','id');
    }
}
