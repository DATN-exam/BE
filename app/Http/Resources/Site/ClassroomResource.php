<?php

namespace App\Http\Resources\Site;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
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
            'name' => $this->name,
            'teacher_id' => $this->teacher_id,
            'teacher' => StudentResource::make($this->whenLoaded('teacher')),
            'status' => $this->status->name,
            'description' => $this->description,
            'count_student' => $this->students_count,
            'avatar' => $this->avatar,
            'quantity_student' => $this->whenHas('quantity_student', $this->quantity_student)
        ];
    }
}
