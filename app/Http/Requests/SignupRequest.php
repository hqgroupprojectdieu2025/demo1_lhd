<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',       // Ít nhất 1 chữ in hoa
                'regex:/[a-z]/',       // Ít nhất 1 chữ thường
                'regex:/[0-9]/',       // Ít nhất 1 số
                'regex:/[@$!%*?&]/'    // Ít nhất 1 ký tự đặc biệt
            ],
            'password_confirmation' => 'required|same:password',
            'agree' => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'Họ và tên là trường bắt buộc.',
            'fullname.string' => 'Họ và tên phải là chuỗi ký tự.',
            'fullname.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email có định dạng hợp lệ là abc@example.com.',
            'email.unique' => 'Email đã được đăng ký. Vui lòng đăng nhập hoặc dùng email khác.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
            'password_confirmation.required' => 'Xác nhận mật khẩu là trường bắt buộc.',
            'password_confirmation.same' => 'Xác nhận mật khẩu không khớp.',
            'agree.required' => 'Bạn phải đồng ý với điều khoản và điều kiện.',
            'agree.accepted' => 'Bạn phải đồng ý với điều khoản và điều kiện.',
        ];
    }
}
