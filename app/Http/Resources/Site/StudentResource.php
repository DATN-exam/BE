<?php

namespace App\Http\Resources\Site;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
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
        $check = $this->teacherRegistrations()
            ->where('user_id', $this->id)
            ->whereIn('status', [TeacherRegistrationStatus::ACCEPT, TeacherRegistrationStatus::WAIT])
            ->exists();
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role->name,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dob' => $this->dob,
            'ward_id' => $this->ward_id,
            'address' => $this->address,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'has_teacher_registration' => $check
        ];
    }
}
