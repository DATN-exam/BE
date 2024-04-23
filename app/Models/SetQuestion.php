<?php

namespace App\Models;

use App\Enums\Question\SetQuestionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'status',
        'description',
        'note'
    ];

    protected $casts = [
        'status' => SetQuestionStatus::class,
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'set_question_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
}
