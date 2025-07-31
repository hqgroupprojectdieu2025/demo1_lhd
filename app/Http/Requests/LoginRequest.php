<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|exists:user,email',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email có định dạng hợp lệ là abc@example.com',
            'email.exists' => 'Email không tồn tại, vui lòng kiểm tra lại.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
        ];
    }
}
