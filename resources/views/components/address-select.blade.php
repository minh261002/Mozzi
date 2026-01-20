@props([
    'provinceCode' => null,
    'provinceText' => null,
    'wardCode' => null,
    'wardText' => null,
    'address' => null,
    'provinceName' => 'province_code',
    'wardName' => 'ward_code',
    'addressName' => 'address',
    'provinceId' => 'province-select',
    'wardId' => 'ward-select',
    'addressId' => 'address-input',
    'required' => false,
])

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

<div class="col-md-6 mb-3">
    <label class="form-label" for="{{ $provinceId }}">Tỉnh/Thành phố</label>
    <select id="{{ $provinceId }}" name="{{ $provinceName }}" class="form-control" style="width: 100%"
        {{ $required ? 'required' : '' }}>
        @if ($provinceCode && $provinceText)
            <option value="{{ $provinceCode }}" selected>{{ $provinceText }}</option>
        @endif
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label" for="{{ $wardId }}">Phường/Xã</label>
    <select id="{{ $wardId }}" name="{{ $wardName }}" class="form-control" style="width: 100%"
        {{ $required ? 'required' : '' }}>
        @if ($wardCode && $wardText)
            <option value="{{ $wardCode }}" selected>{{ $wardText }}</option>
        @endif
    </select>
</div>

<div class="col-12">
    <label class="form-label" for="{{ $addressId }}">Địa chỉ chi tiết</label>
    <input type="text" id="{{ $addressId }}" name="{{ $addressName }}" class="form-control"
        value="{{ old($addressName, $address) }}" placeholder="Số nhà, đường..." {{ $required ? 'required' : '' }} />
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function() {
            var provinceSelect = $('#{{ $provinceId }}');
            var wardSelect = $('#{{ $wardId }}');

            var provincesUrl = @json(route('api.provinces'));
            var wardsUrl = @json(route('api.wards'));

            function initProvinceSelect2() {
                provinceSelect.select2({
                    placeholder: 'Chọn Tỉnh/TP',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0,
                    theme: 'bootstrap-5'
                });
            }

            function initWardSelect2() {
                wardSelect.select2({
                    placeholder: 'Chọn Phường/Xã',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0,
                    theme: 'bootstrap-5'
                });
            }

            function loadProvinces(preselectCode) {
                $.getJSON(provincesUrl).then(function(data) {
                    provinceSelect.empty().append('<option></option>');
                    (data.results || []).forEach(function(p) {
                        provinceSelect.append(
                            $('<option>', {
                                value: p.id,
                                text: p.text
                            })
                        );
                    });

                    initProvinceSelect2();

                    if (preselectCode) {
                        provinceSelect.val(preselectCode).trigger('change.select2');
                    } else {
                        wardSelect.prop('disabled', true);
                        initWardSelect2();
                    }
                });
            }

            function loadWards(provinceCode, preselectWardCode) {
                wardSelect.prop('disabled', !provinceCode);
                wardSelect.empty().append('<option></option>');

                if (!provinceCode) {
                    initWardSelect2();
                    return;
                }

                $.getJSON(wardsUrl, {
                    province_code: provinceCode
                }).then(function(data) {
                    (data.results || []).forEach(function(w) {
                        wardSelect.append(
                            $('<option>', {
                                value: w.id,
                                text: w.text
                            })
                        );
                    });

                    if (wardSelect.hasClass('select2-hidden-accessible')) {
                        wardSelect.trigger('change.select2');
                    } else {
                        initWardSelect2();
                    }

                    if (preselectWardCode) {
                        wardSelect.val(preselectWardCode).trigger('change.select2');
                    }
                });
            }

            var preProvince = {!! $provinceCode ? json_encode($provinceCode) : 'null' !!};
            var preWard = {!! $wardCode ? json_encode($wardCode) : 'null' !!};

            loadProvinces(preProvince);

            if (preProvince) {
                loadWards(preProvince, preWard);
            }

            provinceSelect.on('change', function() {
                var code = $(this).val() || null;
                wardSelect.val(null).trigger('change');
                loadWards(code, null);
            });
        })();
    </script>
@endpush
