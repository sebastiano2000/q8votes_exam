<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompoundRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'user_id' => 'required',
        ];
    }
    
    public function messages():array
    {
        return [
            'name.required' => 'يجب عليك إدخال الاسم',
            'user_id.required' => 'يجب عليك اختيار صاحب المحفظة العقارية'
        ];
    }
}
