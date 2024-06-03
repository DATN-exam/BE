<?php

namespace App\Http\Resources\Site\Teacher\Question;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SetQuestionResource extends JsonResource
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
            'teacher_id' => $this->teacher_id,
            'title' => $this->title,
            'name' => $this->title,
            'status' => $this->status->name,
            'description' => $this->description,
            'note' => $this->note,
            'question_hard_count' => $this->whenHas('question_hard_count'),
            'question_medium_count' => $this->whenHas('question_medium_count'),
            'question_easy_count' => $this->whenHas('question_easy_count'),
        ];
    }
}
