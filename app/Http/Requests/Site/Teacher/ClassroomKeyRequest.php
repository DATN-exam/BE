<?php

namespace App\Http\Requests\Site\Teacher;

use App\Enums\Classroom\ClassroomKeyStatus;
use App\Rules\InEnumRule;
use Illuminate\Foundation\Http\FormRequest;

class ClassroomKeyRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'status' => [
                new InEnumRule(ClassroomKeyStatus::getKeys())
            ],
            'quantity' => ['numeric', 'min:1'],
            'expired' => [
                'date_format:' . config('define.date_format'),
                'after_or_equal:now'
            ],
        ];
    }
}
