<?php

namespace App\Http\Requests;

use App\IdeaStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdeaStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(IdeaStatus::class)],
            'links' => ['nullable', 'array'],
            'links.*' => ['url'],
            'steps' => ['nullable', 'array'],
            'steps.*' => ['string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
