<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherRegistrationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user_name' => $this->user->fullName,
            'user_first_name' => $this->user->first_name,
            'user_last_name' => $this->user->last_name,
            'status' => $this->status->name,
            'description' => $this->description,
            'employee_cofirm_id' => $this->employee_cofirm_id,
            'reason' => $this->reason,
        ];
    }
}
