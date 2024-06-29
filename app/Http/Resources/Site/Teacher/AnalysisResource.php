<?php

namespace App\Http\Resources\Site\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalysisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'classrooms_count' => $this['my_classrooms_count'],
            'set_question_count' => $this['set_question_count'],
            'classroom_active_count' => $this['classroom_active_count'],
            'set_question_active_count' => $this['set_question_active_count'],
            'classrooms' => $this['my_classrooms'],
            'set_question' => $this['set_question'],
        ];
    }
}
