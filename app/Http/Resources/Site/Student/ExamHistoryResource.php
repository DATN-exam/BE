<?php

namespace App\Http\Resources\Site\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamHistoryResource extends JsonResource
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
            'exam_id' => $this->exam_id,
            'exam' => ExamResource::make($this->whenLoaded('exam')),
            'student_id' => $this->student_id,
            'start_time' => $this->start_time,
            'type' => $this->type->name,
            'exam_answers' => ExamAnswerResource::collection($this->whenLoaded('examAnswers'))
        ];
    }
}
