<?php

namespace App\Http\Resources\Site\Teacher;

use App\Enums\Classroom\ClassroomStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dob' => $this->dob,
            'ward_id' => $this->ward_id,
            'address' => $this->address,
            'description' => $this->description,
            'type_join' => $this->type_join,
            'classroom_status' => ClassroomStatus::getKeyByValue($this->classroom_status),
        ];
    }
}
