<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndustryCategoryRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];

        // Для обновления добавляем уникальность с исключением текущей записи
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'] .= '|unique:industry_categories,name,' . $this->route('industry_category');
        } else {
            $rules['name'] .= '|unique:industry_categories,name';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Название категории обязательно.',
            'name.string' => 'Название категории должно быть строкой.',
            'name.max' => 'Название категории не должно превышать 255 символов.',
            'name.unique' => 'Категория с таким названием уже существует.',
            'description.string' => 'Описание должно быть строкой.',
            'icon.string' => 'Иконка должна быть строкой.',
            'icon.max' => 'Иконка не должна превышать 255 символов.',
            'color.string' => 'Цвет должен быть строкой.',
            'color.max' => 'Цвет не должен превышать 7 символов.',
            'is_active.boolean' => 'Статус активности должен быть логическим значением.',
            'sort_order.integer' => 'Порядок сортировки должен быть числом.',
            'sort_order.min' => 'Порядок сортировки не может быть отрицательным.',
        ];
    }
}
