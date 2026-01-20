@extends('layouts.guest')

@section('title', 'Quên mật khẩu')

@section('content')
    <form id="forgot-password-form" action="{{ route('password.email') }}" method="POST" autocomplete="off" novalidate>
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <button type="submit" id="forgot-password-button" class="btn btn-primary w-100">
            Quên mật khẩu
        </button>
    </form>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#forgot-password-form').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const button = $('#forgot-password-button');

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
