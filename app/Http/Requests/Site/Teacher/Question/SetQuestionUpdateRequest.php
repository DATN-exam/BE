<?php

namespace App\Http\Requests\Site\Teacher\Question;

use App\Enums\Question\SetQuestionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetQuestionUpdateRequest extends FormRequest
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
            'title' => ['string', 'max:255'],
            'status' => [Rule::in([
                SetQuestionStatus::ACTIVE->name, SetQuestionStatus::BLOCK->name
            ])],
            'note' => ['string', 'max:255'],
            'description' => ['string', 'max:255'],
        ];
    }
}
