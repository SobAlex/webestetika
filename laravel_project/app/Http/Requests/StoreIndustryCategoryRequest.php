<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIndustryCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:industry_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Название категории обязательно для заполнения.',
            'name.max' => 'Название категории не должно превышать 255 символов.',
            'slug.unique' => 'URL-слаг уже используется другой категорией.',
            'slug.max' => 'URL-слаг не должен превышать 255 символов.',
            'icon.required' => 'Иконка обязательна для заполнения.',
            'icon.max' => 'Название иконки не должно превышать 255 символов.',
            'color.required' => 'Цвет обязателен для заполнения.',
            'color.regex' => 'Цвет должен быть в формате HEX (#000000).',
            'sort_order.integer' => 'Порядок сортировки должен быть числом.',
            'sort_order.min' => 'Порядок сортировки не может быть отрицательным.',
        ];
    }

}
