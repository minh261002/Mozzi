<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Requests\Auth\ForgotPasswordRequest;
use App\Admin\Http\Requests\Auth\LoginRequest;
use App\Admin\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function login(): View
    {
        return view("auth.login");
    }

    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $key = 'login.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            notyf()->error("Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau {$seconds} giây.");

            throw ValidationException::withMessages([
                'email' => "Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau {$seconds} giây.",
            ]);
        }

        $data = $request->validated();
        $remember = $data['remember'] ?? false;
        unset($data['remember']);

        if (Auth::attempt($data, $remember)) {
            RateLimiter::clear($key);

            $request->session()->regenerate();

            Log::info('User logged in', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            notyf()->success('Xin chào ' . Auth::user()->name);
            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($key, 60);

        Log::warning('Failed login attempt', [
            'email' => $data['email'] ?? null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        notyf()->error('Thông tin đăng nhập không chính xác');
        return redirect()->back()->withInput($request->only('email'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        notyf()->success('Đăng xuất thành công');
        return redirect()->route('login');
    }

    public function forgotPassword(): View
    {
        return view("auth.forgot-password");
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $key = 'forgot-password.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            notyf()->error("Quá nhiều yêu cầu. Vui lòng thử lại sau {$minutes} phút.");
            return redirect()->back();
        }

        $data = $request->validated();

        $status = Password::sendResetLink(['email' => $data['email']]);

        RateLimiter::hit($key, 600);

        Log::info('Password reset link requested', [
            'email' => $data['email'],
            'ip' => $request->ip(),
            'status' => $status,
        ]);

        if ($status == Password::RESET_LINK_SENT) {
            notyf()->success('Kiểm tra email để đặt lại mật khẩu');
            return redirect()->route('login');
        }

        notyf()->error('Đã có lỗi xảy ra, vui lòng thử lại sau');
        return redirect()->back();
    }

    public function showResetForm(Request $request, $token = null): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $key = 'reset-password.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            notyf()->error("Quá nhiều yêu cầu. Vui lòng thử lại sau {$minutes} phút.");
            return redirect()->back();
        }

        $data = $request->validated();

        $status = Password::reset(
            $data,
            function ($user) use ($data, $request) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                $this->logoutOtherSessions($user);

                Log::info('Password reset successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                ]);

                Auth::login($user);
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            RateLimiter::clear($key);

            notyf()->success('Đặt lại mật khẩu thành công. Tất cả phiên đăng nhập cũ đã bị đăng xuất.');
            return redirect()->route('dashboard');
        }

        RateLimiter::hit($key, 3600);

        notyf()->error('Đã có lỗi xảy ra, vui lòng thử lại sau');
        return redirect()->back();
    }

    protected function logoutOtherSessions($user): void
    {
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        $user->setRememberToken(Str::random(60));
        $user->save();
    }
}
