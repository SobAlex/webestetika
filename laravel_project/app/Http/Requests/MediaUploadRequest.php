<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
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
            'file' => 'required|file|max:10240', // 10MB
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Файл обязателен для загрузки.',
            'file.file' => 'Загруженный файл должен быть валидным файлом.',
            'file.max' => 'Размер файла не должен превышать 10MB.',
            'alt_text.max' => 'Альтернативный текст не должен превышать 255 символов.',
            'description.max' => 'Описание не должно превышать 1000 символов.',
        ];
    }
}
