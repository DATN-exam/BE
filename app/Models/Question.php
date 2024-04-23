<?php

namespace App\Models;

use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'set_question_id',
        'question',
        'type',
        'status',
        'score',
        'img',
        'is_testing'
    ];

    protected $casts = [
        'status' => QuestionStatus::class,
        'type' => QuestionType::class,
        'is_testing' => 'boolean',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class,'question_id','id');
    }
}
