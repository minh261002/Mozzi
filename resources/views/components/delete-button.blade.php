@props([
    'id',
    'deleteUrl',
    'buttonClass' => 'btn btn-sm btn-danger',
    'iconClass' => 'ti ti-trash fs-1',
    'modalSize' => 'modal-sm',
    'title' => 'Cảnh báo',
    'message' => 'Dữ liệu sẽ bị xóa vĩnh viễn khỏi hệ thống <br/> và không thể khôi phục. Bạn có chắc muốn xóa?',
    'cancelText' => 'Huỷ',
    'confirmText' => 'Xóa',
])

@php
    $modalId = 'modal-delete-' . $id;
@endphp

<a href="#" class="{{ $buttonClass }}" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
    <i class="{{ $iconClass }}"></i>
    @isset($slot)
        <span>{{ $slot }}</span>
    @endisset

</a>

<div class="modal modal-blur fade" id="{{ $modalId }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }} modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <i class="ti ti-alert-triangle text-danger" style="font-size: 64px"></i>
                <h3>{{ $title }}</h3>
                <div class="text-secondary">{!! $message !!}</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn w-100" data-bs-dismiss="modal">{{ $cancelText }}</a>
                        </div>
                        <div class="col">
                            <a href="{{ $deleteUrl }}" class="btn btn-danger w-100 btn-delete"
                                data-bs-dismiss="modal">
                                {{ $confirmText }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
