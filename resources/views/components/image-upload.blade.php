@props([
    'name' => 'image',
    'label' => 'Ảnh đại diện',
    'accept' => 'image/*',
    'id' => null,
    'placeholder' => '/assets/img/not-found.jpg',
    'initial' => null,
])

@php
    $uid = $id ?: 'imgup-' . \Illuminate\Support\Str::uuid()->toString();
    $inputId = $uid . '-input';
    $imgId = $uid . '-img';
    $placeholderUrl = asset($placeholder);

    $initialItem = null;
    if (is_string($initial) && !empty($initial)) {
        $initialItem = ['url' => $initial, 'path' => $initial];
    } elseif (is_array($initial)) {
        $initialItem = [
            'url' => $initial['url'] ?? ($initial['path'] ?? null),
            'path' => $initial['path'] ?? ($initial['url'] ?? null),
        ];
        if (empty($initialItem['url'])) {
            $initialItem = null;
        }
    }
    $removeInputName = rtrim($name, '[]') . '_remove';
@endphp

<div id="{{ $uid }}" class="image-upload-single">
    @if ($label)
        <label class="form-label d-block" for="{{ $inputId }}">{{ $label }}</label>
    @endif

    <input type="file" id="{{ $inputId }}" name="{{ $name }}" class="d-none" accept="{{ $accept }}" />

    <div class="preview-wrapper mb-2 position-relative" role="button" tabindex="0" data-trigger>
        <img id="{{ $imgId }}" src="{{ $initialItem['url'] ?? $placeholderUrl }}"
            data-placeholder="{{ $placeholderUrl }}" alt="preview" class="img-thumbnail preview-image">
        <button type="button" class="btn btn-sm btn-danger shadow delete-btn {{ !$initialItem ? 'd-none' : '' }}"
            data-action="remove" title="Xóa ảnh">
            <i class="ti ti-trash"></i>
        </button>
        <div class="small text-muted mt-1 text-center">Nhấn để chọn ảnh</div>
    </div>

    @if ($initialItem)
        <div class="d-none">
            <input class="form-check-input" type="checkbox" name="{{ $removeInputName }}"
                value="{{ $initialItem['path'] }}">
        </div>
    @endif
</div>

@push('styles')
    <style>
        .image-upload-single .preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-upload-single .delete-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            line-height: 1;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            const root = document.getElementById(@json($uid));
            if (!root) return;

            const input = root.querySelector('#' + @json($inputId));
            const img = root.querySelector('#' + @json($imgId));
            const trigger = root.querySelector('[data-trigger]');
            const btnRemove = root.querySelector('[data-action="remove"]');
            const removeCheckbox = root.querySelector('input[type="checkbox"][name="{{ $removeInputName }}"]');
            const placeholder = img.getAttribute('data-placeholder');

            function setPreview(file) {
                if (file) {
                    const url = URL.createObjectURL(file);
                    img.src = url;
                    btnRemove?.classList.remove('d-none'); // hiện nút xóa khi có ảnh
                    if (removeCheckbox) removeCheckbox.checked = false; // có ảnh mới => không xóa ảnh cũ
                } else {
                    img.src = placeholder;
                    btnRemove?.classList.add('d-none'); // ẩn nút xóa khi không có ảnh
                }
            }

            input.addEventListener('change', (e) => {
                const file = (e.target.files || [])[0] || null;
                setPreview(file);
            });

            trigger?.addEventListener('click', () => input.click());
            trigger?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') input.click();
            });

            btnRemove?.addEventListener('click', (e) => {
                e.stopPropagation(); // tránh mở hộp chọn file
                e.preventDefault();
                input.value = '';
                setPreview(null);
                if (removeCheckbox) removeCheckbox.checked = true; // đánh dấu xóa ảnh cũ (nếu có)
            });
        })();
    </script>
@endpush
