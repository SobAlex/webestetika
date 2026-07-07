<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\IndustryCategory;

class StoreProjectCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Разрешаем всем авторизованным пользователям
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Получаем все активные категории отраслей
        $validIndustries = IndustryCategory::active()->pluck('slug')->implode(',');

        $rules = [
            'case_id' => 'nullable|string|max:255|unique:cases,case_id',
            'title' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'industry' => 'required|string|in:' . $validIndustries,
            'period' => 'required|string|max:100',
            'image' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'result_1' => 'nullable|string|max:500',
            'result_2' => 'nullable|string|max:500',
            'result_3' => 'nullable|string|max:500',
            'result_4' => 'nullable|string|max:500',
            'result_5' => 'nullable|string|max:500',
            'result_6' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
            'service_link_text' => 'nullable|string|max:255',
            'service_link_url' => 'nullable|string|max:500',
        ];

        // Добавляем правила для метрик до/после
        for ($i = 1; $i <= 4; $i++) {
            $rules["metric_name_{$i}"] = 'nullable|string|max:100';
            $rules["metric_before_{$i}"] = 'nullable|string|max:100';
            $rules["metric_after_{$i}"] = 'nullable|string|max:100';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Название кейса обязательно для заполнения.',
            'client.required' => 'Название клиента обязательно для заполнения.',
            'industry.required' => 'Отрасль обязательна для заполнения.',
            'industry.in' => 'Выберите корректную отрасль.',
            'period.required' => 'Период работы обязателен для заполнения.',
            'image.required' => 'Изображение кейса обязательно для заполнения.',
            'description.required' => 'Описание кейса обязательно для заполнения.',
            'case_id.unique' => 'Кейс с таким ID уже существует.',
        ];
    }
}
