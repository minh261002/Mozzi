@props([
    'id' => 'select2-' . uniqid(),
    'name' => '',
    'label' => '',
    'placeholder' => 'Chọn...',
    'options' => [],
    'selected' => null,
    'multiple' => false,
    'required' => false,
    'disabled' => false,
    'ajax' => false,
    'ajaxUrl' => '',
    'allowClear' => true,
    'tags' => false,
    'containerClass' => '',
    'labelClass' => 'form-label',
    'selectClass' => 'form-select',
    'error' => null,
])

<div class="{{ $containerClass }}">
    @if ($label)
        <label for="{{ $id }}" class="{{ $labelClass }}">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select id="{{ $id }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        class="{{ $selectClass }} {{ $error ? 'is-invalid' : '' }}" {{ $multiple ? 'multiple' : '' }}
        {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }} data-placeholder="{{ $placeholder }}">

        @if (!$multiple && !$ajax)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if (!$ajax)
            @foreach ($options as $value => $text)
                <option value="{{ $value }}"
                    {{ (is_array($selected) ? in_array($value, $selected) : $value == $selected) ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        @endif
    </select>

    @if ($error)
        <div class="invalid-feedback d-block">
            {{ $error }}
        </div>
    @endif

    {{ $slot }}
</div>

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/vi.js"></script>
    @endpush
@endonce

@push('scripts')
    <script>
        $(document).ready(function() {
            var config = {
                theme: 'bootstrap-5',
                width: '100%',
                language: 'vi',
                placeholder: '{{ $placeholder }}',
                allowClear: {{ $allowClear ? 'true' : 'false' }},
                tags: {{ $tags ? 'true' : 'false' }}
            };

            @if ($ajax && $ajaxUrl)
                config.ajax = {
                    url: '{{ $ajaxUrl }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data || data.results || data,
                            pagination: {
                                more: (params.page * 10) < (data.total || 0)
                            }
                        };
                    },
                    cache: true
                };
                config.minimumInputLength = 0;
            @endif

            $('#{{ $id }}').select2(config);

            // Handle dynamic changes
            $('#{{ $id }}').on('change', function() {
                $(this).trigger('select2:change');
            });
        });
    </script>
@endpush
