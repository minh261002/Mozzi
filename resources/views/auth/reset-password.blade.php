@extends('layouts.guest')

@section('title', 'Đặt lại mật khẩu')

@section('content')
    <form id="reset-password-form" action="{{ route('password.update') }}" method="POST" autocomplete="off" novalidate>
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <div class="input-group input-group-flat">
                <input type="password" id="password" name="password" class="form-control" required>

                <span class="input-group-text">
                    <a href="#" class="toggle-password link-secondary" data-target="password">
                        <i class="ti ti-eye icon icon-1"></i>
                        <i class="ti ti-eye-off icon icon-1 d-none"></i>
                    </a>
                </span>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu</label>
            <div class="input-group input-group-flat">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    required>

                <span class="input-group-text">
                    <a href="#" class="toggle-password link-secondary" data-target="password_confirmation">
                        <i class="ti ti-eye icon icon-1"></i>
                        <i class="ti ti-eye-off icon icon-1 d-none"></i>
                    </a>
                </span>
            </div>
        </div>

        <button type="submit" id="reset-password-button" class="btn btn-primary w-100 mt-3">
            Đặt lại mật khẩu
        </button>
    </form>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.toggle-password').on('click', function(e) {
                e.preventDefault();

                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const icons = $(this).find('i');

                const isPassword = input.attr('type') === 'password';
                input.attr('type', isPassword ? 'text' : 'password');

                icons.eq(0).toggleClass('d-none', isPassword);
                icons.eq(1).toggleClass('d-none', !isPassword);
            });

            $('#reset-password-form').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const button = $('#reset-password-button');

                button.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm me-1"></span>
            Đang xử lý...
        `);

                setTimeout(() => form.submit(), 500);
            });

        });
    </script>
@endpush
