<?php

namespace App\Http\Resources\Site\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_history_id' => $this->exam_history_id,
            'question_id' => $this->question_id,
            'is_correct' => $this->is_correct,
            'answer_text' => $this->answer_text,
            'answer_id' => $this->answer_id,
            'score' => $this->score,
            'question' => QuestionResource::make($this->whenLoaded('question')),
        ];
    }
}
