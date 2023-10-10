<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
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
            'user_id.*' => 'required',
            'compound_id.*' => 'required',
        ];
    }
    
    public function messages():array
    {
        $messages = [
            'name.required' => 'يجب عليك إدخال الاسم',
        ];
    
        foreach ($this->get('user_id') as $key => $val) {
            $messages["user_id.$key.required"] = 'يجب عليك اختيار صاحب المحفظة العقارية';
        }
        
        foreach ($this->get('compound_id') as $key => $val) {
            $messages["compound_id.$key.required"] = 'يجب عليك إدخال اختيار المحفظة العقارية';
        }
        
        return $messages;
    }
}
