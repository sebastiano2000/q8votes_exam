<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
            'name' => 'required|min:3',
            'phone' => ['required',function($attribute,$value,$fail){
                $count = User::where('phone', $value)->where('id', '!=', request()->id)->count();

                if ( $count > 0 ) {
                    return $fail('رقم الهاتف مسجل لدينا بالفعل');
                }
            }],
            'email' => ['required',function($attribute,$value,$fail){
                $count = User::where('email', $value)->where('id', '!=', request()->id)->count();

                if ( $count > 0 ) {
                    return $fail('لبريد الالكتروني مسجل لدينا بالفعل');
                }
            }],
            'password' => ['confirmed', function($attribute,$value,$fail){
                if ( request()->id) {
                    return $fail('يجب عليك إدخال  كلمة السر');
                }
            }],
        ];
    }

    public function messages():array
    {
        return [
            'name.required' => 'يجب عليك إدخال الاسم',
            'phone.required' => 'يجب عليك إدخال رقم الهاتف',
            'email.required' => 'يجب عليك إدخال لبريد الالكتروني',
            'password.required' => 'يجب عليك إدخال  كلمة السر',
            'password.confirmed' => 'تأكيد كلمة السر غير متطابق',
            'phone.unique' => 'رقم الهاتف مسجل لدينا بالفعل',
            'email.unique' => 'البريد الالكتروني مسجل لدينا بالفعل',
        ];
    }
}
