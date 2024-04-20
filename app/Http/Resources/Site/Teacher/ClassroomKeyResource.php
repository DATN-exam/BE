<?php

namespace App\Http\Resources\Site\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomKeyResource extends JsonResource
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
            'key' => $this->key,
            'classroom_id' => $this->classroom_id,
            'status' => $this->status->name,
            'quantity' => $this->quantity,
            'remaining' => $this->remaining,
            'expired' => $this->expired,
        ];
    }
}
