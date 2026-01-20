<?php

namespace App\Admin\Http\Requests\Auth;

use App\Base\BaseRequest;

class LoginRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'remember' => $this->boolean('remember'),
        ]);
    }

    protected function rulesPost(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có tối thiểu 6 ký tự',
        ];
    }
}
