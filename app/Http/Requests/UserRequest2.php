<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest2 extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'password' => 'required|confirmed',
        ];
    }

    public function messages():array
    {
        return [
            'password.required' => 'يجب عليك إدخال  كلمة السر',
            'password.confirmed' => 'تأكيد كلمة السر غير متطابق',
        ];
    }
}
