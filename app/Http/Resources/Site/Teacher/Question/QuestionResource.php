<?php

namespace App\Http\Resources\Site\Teacher\Question;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'set_question_id' => $this->set_question_id,
            'question' => getQuestionHtml($this->question),
            'type' => $this->type->name,
            'status' => $this->status->name,
            'level' => $this->level->name,
            'score' => $this->score,
            'img' => $this->img,
            'is_testing' => $this->is_testing,
            'answers' =>AnswerResource::collection($this->whenLoaded('answers'))
        ];
    }
}
