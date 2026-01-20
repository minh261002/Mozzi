@php
    $adminSidebar = config('admin_sidebar');
    $user = Auth::user();
@endphp

<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-brand navbar-brand-autodark ps-3" style="display: flex; justify-content: start;">
            <a href="{{ route('admin.dashboard') }}" aria-label="Logo">
                <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo" class="navbar-brand-image">
            </a>
        </div>

        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item d-none d-lg-flex me-3">
                <div class="btn-list">
                    <a href="https://github.com/tabler/tabler" class="btn btn-5" target="_blank" rel="noreferrer">
                        <i class="ti ti-brand-github"></i>
                        Source code
                    </a>
                    <a href="https://github.com/sponsors/codecalm" class="btn btn-6" target="_blank" rel="noreferrer">
                        <i class="ti ti-heart"></i>
                        Sponsor
                    </a>
                </div>
            </div>
            <div class="d-none d-lg-flex">
                <div class="nav-item">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode"
                        data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class='ti ti-moon'></i>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode"
                        data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class="ti ti-sun"></i>
                    </a>
                </div>
                <div class="nav-item dropdown d-none d-md-flex">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show notifications" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-bell"></i>
                        <span class="badge bg-red"></span>
                    </a>
                </div>
                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show app menu" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-apps"></i>
                    </a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar avatar-sm"
                        style="background-image: url({{ $user->avatar ?? avatar_placeholder($user->name) }})">
                    </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>
                            {{ $user->name }}
                        </div>
                        <div class="mt-1 small text-secondary">
                            {{-- {{ $user->role[0]->title }} --}}
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item {{ setSidebarActive(['admin.dashboard']) }}">
                    <a class="nav-link  {{ setSidebarShow(['admin.dashboard']) }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-home-2 fs-2"></i>
                        </span>
                        <span class="nav-link-title">
                            Dashboard
                        </span>
                    </a>
                </li>

                @foreach ($adminSidebar as $menu)
                    @php
                        $hasParentAccess =
                            $user &&
                            ($user->checkPermissions($menu['permission']) || in_array('Dev', $menu['permission']));
                    @endphp
                    @if ($hasParentAccess)
                        <li class="nav-item dropdown {{ setSidebarActive($menu['active']) }}">
                            <a class="nav-link dropdown-toggle {{ setSidebarShow($menu['show']) }}" href="#"
                                data-bs-toggle="dropdown" data-bs-auto-close="false" role="button"
                                aria-expanded="true">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="{{ $menu['icon'] }}"></i>
                                </span>
                                <span class="nav-link-title">{{ $menu['title'] }}</span>
                            </a>

                            @if (!empty($menu['children']))
                                @php
                                    $canViewChildren = $hasParentAccess || in_array('Root', $menu['permission']);
                                @endphp
                                @if ($canViewChildren)
                                    <div class="dropdown-menu {{ setSidebarShow($menu['show']) }}">
                                        <div class="dropdown-menu-columns">
                                            @foreach ($menu['children'] as $child)
                                                @if (isset($child['permission']) && !$user?->can($child['permission']))
                                                    @continue
                                                @endif
                                                <a class="dropdown-item" href="{{ route($child['route']) }}">
                                                    <i class="{{ $child['icon'] }} me-2"></i>
                                                    {{ $child['title'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</aside>
