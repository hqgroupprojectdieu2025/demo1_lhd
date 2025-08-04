<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',       // Ít nhất 1 chữ in hoa
                'regex:/[a-z]/',       // Ít nhất 1 chữ thường
                'regex:/[0-9]/',       // Ít nhất 1 số
                'regex:/[@$!%*?&]/',   // Ít nhất 1 ký tự đặc biệt
            ],
            'password_confirmation' => 'required|same:password',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $password = $this->input('password');
            
            if ($password) {
                $errors = [];
                
                if (!preg_match('/[A-Z]/', $password)) {
                    $errors[] = 'Mật khẩu phải chứa ít nhất 1 chữ in hoa.';
                }
                
                if (!preg_match('/[a-z]/', $password)) {
                    $errors[] = 'Mật khẩu phải chứa ít nhất 1 chữ thường.';
                }
                
                if (!preg_match('/[0-9]/', $password)) {
                    $errors[] = 'Mật khẩu phải chứa ít nhất 1 số.';
                }
                
                if (!preg_match('/[@$!%*?&]/', $password)) {
                    $errors[] = 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt (@$!%*?&).';
                }
                
                foreach ($errors as $error) {
                    $validator->errors()->add('password', $error);
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'token.required' => 'Token không hợp lệ.',
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu mới là trường bắt buộc.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
            'password_confirmation.required' => 'Xác nhận mật khẩu là trường bắt buộc.',
            'password_confirmation.same' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
} 