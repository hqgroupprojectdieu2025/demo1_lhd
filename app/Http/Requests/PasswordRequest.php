<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            'password_old' => 'require',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',       // Ít nhất 1 chữ in hoa
                'regex:/[a-z]/',       // Ít nhất 1 chữ thường
                'regex:/[0-9]/',       // Ít nhất 1 số
                'regex:/[@$!%*?&]/'    // Ít nhất 1 ký tự đặc biệt
            ],
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages(){
        return [
            'password_old.required' => 'Mật khẩu hiện tại là trường bắt buộc.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
            'password_confirmation.required' => 'Xác nhận mật khẩu là trường bắt buộc.',
            'password_confirmation.same' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
