<?php

namespace App\Http\Resources\Site\Teacher;

use App\Http\Resources\Site\Teacher\Question\SetQuestionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
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
            'set_question' => SetQuestionResource::make($this->whenLoaded('setQuestion')),
            'set_question_id' => $this->set_question_id,
            'note' => $this->note,
            'name' => $this->name,
            'working_time' => $this->working_time,
            'start_date' => $this->start_date,
            'is_show_result' => $this->is_show_result,
            'end_date' => $this->end_date,
            'number_question_hard' => $this->number_question_hard,
            'number_question_medium' => $this->number_question_medium,
            'number_question_easy' => $this->number_question_easy,
        ];
    }
}
