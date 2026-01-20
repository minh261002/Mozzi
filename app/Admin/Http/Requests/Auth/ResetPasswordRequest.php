<?php

namespace App\Admin\Http\Requests\Auth;

use App\Base\BaseRequest;

class ResetPasswordRequest extends BaseRequest
{
    protected function rulesPost(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email,deleted_at,NULL'],
            'token' => ['required'],
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống',
            'token.required' => 'Token không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'password.confirmed' => 'Mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ];
    }
}
