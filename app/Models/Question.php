<?php

namespace App\Models;

use App\Enums\Question\QuestionLevel;
use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'set_question_id',
        'question',
        'level',
        'type',
        'status',
        'score',
        'img',
        'is_testing'
    ];

    protected $casts = [
        'status' => QuestionStatus::class,
        'type' => QuestionType::class,
        'type' => QuestionType::class,
        'level' => QuestionLevel::class,
        'is_testing' => 'boolean',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function setQuestion()
    {
        return $this->belongsTo(SetQuestion::class, 'set_question_id', 'id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
