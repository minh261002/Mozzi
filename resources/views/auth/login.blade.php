@extends('layouts.guest')

@section('title', 'Đăng nhập')

@section('content')
    <form id="login-form" action="{{ route('authenticate') }}" method="POST" autocomplete="off" novalidate>
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Mật khẩu
                <span class="form-label-description">
                    <a href="{{ route('password.forgot') }}">Quên mật khẩu?</a>
                </span>
            </label>

            <div class="input-group input-group-flat">
                <input type="password" id="password" name="password" class="form-control" required>

                <span class="input-group-text">
                    <a href="#" id="toggle-password" class="link-secondary">
                        <i id="eye-open" class="ti ti-eye icon icon-1"></i>
                        <i id="eye-close" class="ti ti-eye-off icon icon-1 d-none"></i>
                    </a>
                </span>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-check">
                <input type="checkbox" name="remember" class="form-check-input">
                <span class="form-check-label">Ghi nhớ</span>
            </label>
        </div>

        <button type="submit" id="login-button" class="btn btn-primary w-100">
            Đăng nhập
        </button>
    </form>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#toggle-password').on('click', function(e) {
                e.preventDefault();

                const input = $('#password');
                const isPassword = input.attr('type') === 'password';

                input.attr('type', isPassword ? 'text' : 'password');
                $('#eye-open').toggleClass('d-none', isPassword);
                $('#eye-close').toggleClass('d-none', !isPassword);
            });

            $('#login-form').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const button = $('#login-button');

                button.prop('disabled', true);
                button.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> '
                );

                setTimeout(() => {
                    form.submit();
                }, 500);
            });

        });
    </script>
@endpush
