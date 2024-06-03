<?php

namespace App\Http\Resources\Site\Student;

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
            'question' => $this->question,
            'type' => $this->type->name,
            'level' => $this->level->name,
            'answers' => AnswerResource::collection($this->whenLoaded('answers'))
        ];
    }
}
