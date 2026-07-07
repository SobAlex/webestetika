<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|regex:/^[a-z0-9\-]*$/',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'required|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название категории обязательно для заполнения.',
            'slug.regex' => 'URL может содержать только строчные буквы, цифры и дефисы.',
            'color.required' => 'Цвет обязателен для заполнения.',
            'color.regex' => 'Цвет должен быть в формате HEX (например: #06b6d4).',
            'icon.required' => 'Иконка обязательна для заполнения.',
        ];
    }
}
