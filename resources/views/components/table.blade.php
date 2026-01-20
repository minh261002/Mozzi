@props([
    'title' => '',
    'createRoute' => null,
    'createText' => 'Thêm mới',
    'columns' => [],
    'data' => null,
    'actions' => null,
    'notice' => null,
    'searchPlaceholder' => 'Tìm kiếm...',
    'emptyText' => 'Không có dữ liệu',
])

@push('styles')
    <link rel="stylesheet" href="/assets/css/custom-table.css">
@endpush

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
        @if ($createRoute)
            <div class="card-actions">
                <a href="{{ $createRoute }}" class="btn btn-primary">
                    <i class="ti ti-plus fs-4 me-1"></i>
                    {{ $createText }}
                </a>
            </div>
        @endif
    </div>

    @if ($notice)
        <div class="text-danger" style="padding: 20px 20px 0 20px;">
            <p>
                <strong>Lưu ý:</strong>
                <span>{!! $notice !!}</span>
            </p>
        </div>
    @endif

    <div class="card-body">
        <!-- Search & Filter -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="{{ $searchPlaceholder }}"
                        value="{{ request('search') }}">
                    <button class="btn btn-primary" type="button" id="searchBtn">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <select id="perPageSelect" class="form-select d-inline-block" style="width: auto;">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 / trang</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 / trang</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / trang</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / trang</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-vcenter table-nowrap table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        @if ($columns)
                            @foreach ($columns as $column)
                                <th class="{{ $column['class'] ?? '' }}">
                                    {{ $column['label'] }}
                                </th>
                            @endforeach
                        @endif
                        @if ($actions)
                            <th class="text-center">Thao tác</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($data)
                        @forelse ($data as $row)
                            <tr>
                                @if ($columns)
                                    @foreach ($columns as $column)
                                        <td class="{{ $column['class'] ?? '' }}">
                                            @if (isset($column['render']))
                                                {!! $column['render']($row) !!}
                                            @else
                                                {{ data_get($row, $column['field']) ?? '-' }}
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                                @if ($actions)
                                    <td class="text-center">
                                        {!! $actions($row) !!}
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + ($actions ? 1 : 0) }}"
                                    class="text-center text-muted">
                                    {{ $emptyText }}
                                </td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="{{ count($columns) + ($actions ? 1 : 0) }}" class="text-center text-muted">
                                {{ $emptyText }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($data && method_exists($data, 'links'))
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Hiển thị {{ $data->firstItem() ?? 0 }} đến {{ $data->lastItem() ?? 0 }}
                    trong tổng số {{ $data->total() }} bản ghi
                </div>
                <div>
                    {{ $data->appends(request()->query())->links('components.pagination') }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            if (searchInput && searchBtn) {
                function performSearch() {
                    const searchValue = searchInput.value;
                    const perPage = document.getElementById('perPageSelect').value;

                    const url = new URL(window.location.href);
                    url.searchParams.set('search', searchValue);
                    url.searchParams.set('per_page', perPage);
                    url.searchParams.delete('page');

                    window.location.href = url.toString();
                }

                searchBtn.addEventListener('click', performSearch);
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch();
                    }
                });
            }

            // Per page change
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', this.value);
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            }

            // Handle delete button click
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const deleteUrl = $btn.attr('href');
                const $row = $btn.closest('tr');

                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            if (window.notyf) {
                                window.notyf.success(response.message || 'Xóa thành công');
                            }
                            // Remove row with animation
                            $row.fadeOut(300, function() {
                                $(this).remove();
                                // Update row numbers if needed
                                updateRowNumbers();
                            });
                        } else if (response.status === 'error') {
                            if (window.notyf) {
                                window.notyf.error(response.message);
                            }
                        }
                    },
                    error: function(xhr) {
                        let message = 'Có lỗi xảy ra, vui lòng thử lại!';

                        if (xhr.responseJSON) {
                            message = xhr.responseJSON.message || message;
                        } else if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                message = response.message || message;
                            } catch (e) {
                                // Keep default message
                            }
                        }

                        if (window.notyf) {
                            window.notyf.error(message);
                        } else {
                            alert(message);
                        }
                    }
                });
            });

            // Update row numbers after delete
            function updateRowNumbers() {
                $('#dataTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        });
    </script>
@endpush
