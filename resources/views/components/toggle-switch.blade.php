@props([
    'id' => '',
    'checked' => false,
    'route' => '',
    'label' => '',
])

@php
    $uid = $id ?: 'toggle-' . \Illuminate\Support\Str::uuid()->toString();
@endphp

<label class="form-check form-switch" id="{{ $uid }}">
    <input class="form-check-input toggle-switch-input" type="checkbox" {{ $checked ? 'checked' : '' }}
        data-route="{{ $route }}" data-id="{{ $id }}">
    @if ($label)
        <span class="form-check-label">{{ $label }}</span>
    @endif
</label>

@once
    @push('scripts')
        <script>
            $(document).ready(function() {
                $(document).on('change', '.toggle-switch-input', function() {
                    const $checkbox = $(this);
                    const route = $checkbox.data('route');
                    const isChecked = $checkbox.is(':checked');

                    if (!route) {
                        console.error('No route specified for toggle switch');
                        return;
                    }

                    $checkbox.prop('disabled', true);

                    $.ajax({
                        url: route,
                        type: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            is_active: isChecked ? 1 : 0
                        },
                        success: function(response) {
                            // Re-enable checkbox
                            $checkbox.prop('disabled', false);

                            if (response.status === 'success') {
                                // Show success notification
                                if (window.notyf) {
                                    window.notyf.success(response.message ||
                                        'Cập nhật trạng thái thành công');
                                }
                            } else {
                                // Show error and revert checkbox
                                if (window.notyf) {
                                    window.notyf.error(response.message || 'Có lỗi xảy ra');
                                }
                                $checkbox.prop('checked', !isChecked);
                            }
                        },
                        error: function(xhr) {
                            // Re-enable checkbox
                            $checkbox.prop('disabled', false);

                            // Show error notification
                            const message = xhr.responseJSON?.message ||
                                'Có lỗi xảy ra khi cập nhật trạng thái';
                            if (window.notyf) {
                                window.notyf.error(message);
                            }

                            // Revert checkbox state
                            $checkbox.prop('checked', !isChecked);
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
