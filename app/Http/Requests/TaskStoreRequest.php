<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskStoreRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in([TaskStatusEnum::PENDING, TaskStatusEnum::IN_PROGRESS, TaskStatusEnum::COMPLETED])],
            'due_date' => ['nullable', 'date_format:Y-m-d']
        ];
    }
}
