@props(['title', 'pretitle' => null, 'breadcrumbs' => []])

<div class="page-header d-print-none" aria-label="Page header">
    <div class="container-xl">
        <div class="row g-2 align-items-md-center flex-column flex-md-row">
            <div class="col-12 col-md min-w-0">
                @if ($pretitle)
                    <div class="page-pretitle mb-1">{{ $pretitle }}</div>
                @endif

                <h2 class="page-title text-truncate" title="{{ $title }}">{{ $title }}</h2>
            </div>

            @if (!empty($breadcrumbs))
                <div class="col-12 col-md-auto ms-md-auto d-print-none mt-1 mt-md-0">
                    <nav aria-label="Breadcrumb">
                        <ol class="breadcrumb breadcrumb-arrows mb-0 d-flex flex-nowrap overflow-auto"
                            style="--bs-breadcrumb-divider: '/'; scroll-snap-type: x mandatory;">
                            @foreach ($breadcrumbs as $index => $breadcrumb)
                                @php
                                    $isLast = $index === count($breadcrumbs) - 1;
                                    $isActive = $isLast && !isset($breadcrumb['url']);
                                @endphp

                                <li class="breadcrumb-item {{ $isActive ? 'active' : '' }} text-nowrap"
                                    @if ($isActive) aria-current="page" @endif
                                    style="scroll-snap-align: start;">
                                    @if (isset($breadcrumb['url']) && !$isActive)
                                        <a href="{{ $breadcrumb['url'] }}" class="text-truncate d-inline-block"
                                            style="max-width: 240px;">
                                            {{ $breadcrumb['name'] }}
                                        </a>
                                    @else
                                        <span class="text-truncate d-inline-block" style="max-width: 240px;">
                                            {{ $breadcrumb['name'] }}
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>
