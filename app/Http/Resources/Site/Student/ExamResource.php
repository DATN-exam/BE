<?php

namespace App\Http\Resources\Site\Student;

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
            'note'=>$this->note,
            'working_time'=>$this->working_time,
            'set_question_id' => $this->set_question_id,
            'classroom_id' => $this->classroom_id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
