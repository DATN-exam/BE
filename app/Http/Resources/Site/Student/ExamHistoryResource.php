<?php

namespace App\Http\Resources\Site\Student;

use Carbon\Carbon;
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
            'show_result' => Carbon::now()->gt($this->exam->end_date) || $this->exam->is_show_result,
            'total_score' => $this->when(Carbon::now()->gt($this->exam->end_date) || $this->exam->is_show_result, $this->total_score),
            'is_submit' => $this->is_submit,
            'student_id' => $this->student_id,
            'start_time' => $this->start_time,
            'type' => $this->type->name,
            'exam_answers' => ExamAnswerResource::collection($this->whenLoaded('examAnswers')),
            'remaining_time' => remainingTime($this->start_time, $this->exam->working_time),
        ];
    }
}
