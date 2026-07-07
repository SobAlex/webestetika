<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
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
     */
    abstract public function rules(): array;

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'required' => 'Поле :attribute обязательно для заполнения.',
            'string' => 'Поле :attribute должно быть строкой.',
            'email' => 'Поле :attribute должно содержать корректный email адрес.',
            'max' => 'Поле :attribute не должно превышать :max символов.',
            'min' => 'Поле :attribute должно содержать минимум :min символов.',
            'unique' => 'Поле :attribute должно быть уникальным.',
            'exists' => 'Выбранное значение для :attribute недействительно.',
            'boolean' => 'Поле :attribute должно быть логическим значением.',
            'integer' => 'Поле :attribute должно быть целым числом.',
            'array' => 'Поле :attribute должно быть массивом.',
            'image' => 'Поле :attribute должно быть изображением.',
            'mimes' => 'Поле :attribute должно быть файлом одного из типов: :values.',
            'max:2048' => 'Поле :attribute не должно превышать 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'название',
            'title' => 'заголовок',
            'email' => 'email',
            'phone' => 'телефон',
            'message' => 'сообщение',
            'service_name' => 'название услуги',
            'attachment' => 'прикрепленный файл',
            'content' => 'содержимое',
            'description' => 'описание',
            'image' => 'изображение',
            'slug' => 'URL-адрес',
            'is_published' => 'публикация',
            'is_active' => 'активность',
            'sort_order' => 'порядок сортировки',
        ];
    }
}
