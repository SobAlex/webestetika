<?php

namespace App\Http\Requests;

class ServiceOrderRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service_name' => 'required|string|max:255',
            'message' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif',
        ];
    }
}
