<?php

namespace App\Admin\Http\Requests\Auth;

use App\Base\BaseRequest;

class ForgotPasswordRequest extends BaseRequest
{
    protected function rulesPost(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email,deleted_at,NULL']
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ];
    }
}
