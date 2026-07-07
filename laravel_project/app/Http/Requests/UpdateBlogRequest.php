<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|regex:/^[a-z0-9\-]*$/',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'category' => 'required|string|exists:blog_categories,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
            'published_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Название статьи обязательно для заполнения.',
            'category.required' => 'Категория обязательна для заполнения.',
            'category.exists' => 'Выберите корректную категорию.',
            'slug.regex' => 'URL может содержать только строчные буквы, цифры и дефисы.',
            'excerpt.max' => 'Краткое описание не должно превышать 1000 символов.',
            'meta_title.max' => 'Meta Title не должен превышать 255 символов.',
            'meta_description.max' => 'Meta Description не должно превышать 500 символов.',
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Изображение должно быть в формате JPEG, PNG или WebP.',
            'image.max' => 'Размер изображения не должен превышать 5MB.',
        ];
    }

}
