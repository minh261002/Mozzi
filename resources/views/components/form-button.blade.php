@props([
    'title' => 'Thao tác',
    'backUrl' => null,
    'backText' => 'Quay lại',
    'submitText' => 'Lưu',
    'backClass' => 'w-100 btn btn-secondary',
    'submitClass' => 'w-100 btn btn-primary',
    'backIcon' => null,
    'submitIcon' => null,
    'showBack' => true,
])

@php
    $resolvedBackUrl =
        $backUrl ??
        (function () {
            try {
                return route('module.index');
            } catch (Throwable $e) {
                return url('/');
            }
        })();
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
    </div>

    <div class="card-body d-flex gap-2">
        @if ($showBack)
            <a href="{{ $resolvedBackUrl }}" class="{{ $backClass }}">
                @if ($backIcon)
                    <i class="{{ $backIcon }} me-1"></i>
                @endif
                {{ $backText }}
            </a>
        @endif

        <button type="submit" class="{{ $submitClass }}">
            @if ($submitIcon)
                <i class="{{ $submitIcon }} me-1"></i>
            @endif
            {{ $submitText }}
        </button>
    </div>
</div>
