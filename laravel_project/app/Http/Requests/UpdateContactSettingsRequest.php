<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // В реальном приложении здесь должна быть проверка прав доступа
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'settings' => 'required|array',
            'settings.*.id' => 'required|integer|exists:contact_settings,id',
            'settings.*.value' => 'nullable|string|max:1000',
            'settings.*.is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'settings.required' => 'Настройки контактов обязательны для заполнения.',
            'settings.array' => 'Настройки должны быть в виде массива.',
            'settings.*.id.required' => 'ID настройки обязателен.',
            'settings.*.id.exists' => 'Настройка с указанным ID не найдена.',
            'settings.*.value.max' => 'Значение настройки не должно превышать 1000 символов.',
        ];
    }
}
