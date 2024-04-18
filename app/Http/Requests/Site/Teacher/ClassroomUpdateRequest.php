<?php

namespace App\Http\Requests\Site\Teacher;

use App\Enums\Classroom\ClassroomStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassroomUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'min:5', 'max:255'],
            'description' => ['string', 'min:5', 'max:255'],
            'status' => [Rule::in(ClassroomStatus::getKeys())]
        ];
    }
}