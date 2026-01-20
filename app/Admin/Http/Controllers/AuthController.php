<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function login(): View
    {
        return view("auth.login");
    }

    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $remember = $data['remember'] ?? false;
        unset($data['remember']);

        if (Auth::attempt($data, $remember)) {
            $request->session()->regenerate();
            notyf()->success('Xin chào ' . Auth::user()->name);
            return redirect()->route('dashboard');
        }

        notyf()->error('Thông tin đăng nhập không chính xác');
        return redirect()->back()->withInput();
    }
}
