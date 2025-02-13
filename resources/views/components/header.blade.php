<?php

    use App\Models\Roles;
    use App\Models\RoleMenu;

    use App\Models\Menu;

    $role = Roles::where('role_id', Auth::user()->role_id)->first();

    // Ambil menu_id yang diizinkan untuk role ini
    $rolemenu = RoleMenu::where('role_id', Auth::user()->role_id)->first();
    $menuIds = json_decode($rolemenu->menu_id, true);

    // Ambil semua menu sesuai dengan menu_id
    $menus = Menu::whereIn('id', $menuIds)->orderBy('menu_order')->get();

    // Ambil menu utama (parent_id = null)
    $megamenus = Menu::whereNull('parent_id')
        ->orderBy('menu_order')
        ->get()
        ->filter(function ($menu) use ($menus) {
            // Hanya tampilkan menu utama jika memiliki sub-menu dalam daftar menu_id
            return $menus->contains('parent_id', $menu->id);
        });


    // dd($megamenus->toArray());
    $web = App\Models\SettingWeb::first();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $web->site_name ?? env('APP_NAME') }}</title>
    @if(isset($web) && $web->favicon)
        <link rel="shortcut icon" href="data:image/png;base64,{{ $web->favicon }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('dist/img/icon.png') }}" type="image/x-icon">
    @endif

    {{-- <link rel="stylesheet" type="text/css" href="/DataTables-2.1.7/extensions/Editor-2.3.2/css/editor.bootstrap.css"> --}}
 
    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/demo.min.css?1692870487') }} rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        .alert-position-top-right {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            width: 300px;
            display: flex;
            flex-direction: column; /* Memungkinkan alert ditumpuk ke bawah */
            gap: 1px; /* Jarak antara alert */
        }

        .btn-gr {
            background-color: #CAAC14;
            color: white;
        }

        .btn-outline-gr {
            --tblr-btn-color: #CAAC14;
            --tblr-btn-bg: transparent;
            --tblr-btn-border-color: #CAAC14;
            --tblr-btn-hover-color: var(--tblr-primary-fg);
            --tblr-btn-hover-border-color: transparent;
            --tblr-btn-hover-bg: #CAAC14;
            --tblr-btn-active-color: var(--tblr-primary-fg);
            --tblr-btn-active-bg: #CAAC14;
            --tblr-btn-disabled-color: #CAAC14;
            --tblr-btn-disabled-border-color: #CAAC14;
        }

        .navbar {
            --tblr-navbar-active-border-color: #CAAC14;
        }

        .dtable-container {
            max-width: 100% !important;


            table {
                white-space: nowrap !important;
                width:100%!important;
                border-collapse:collapse!important;
            }
        }


        .navbar-vertical .nav-item .nav-link {
            transition: background 0.2s ease, #CAAC14 0.2s ease;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }

        .navbar-vertical .nav-item .nav-link:hover {
            background-color: #f1f5f9;
            color: #CAAC14;
        }

        .navbar-vertical .nav-item .icon {
            color: #CAAC14;
            transition: color 0.2s ease;
        }

        .navbar-vertical .nav-item .nav-link-title {
            font-weight: 500;
        }

        .navbar-brand-image {
            height: 3rem;
            width: auto;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.4.3/css/scroller.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">



    @yield('styles')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.dataTables.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.4.3/js/dataTables.scroller.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('dist/js/select2.min.js') }}"></script>



    {{-- <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script> --}}


</head>
<style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }

</style>
</head>

<body>
    <script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-light shadow-sm">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                    aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                        <img src="{{ asset('images/venora_logo.png') }}" width="150" height="75" alt="Venora System" class="navbar-brand-image me-3">
                        <h3 class="mb-0 d-none d-sm-block">Venora System</h3>
                    </a>
                </h1>
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <!-- Menu Item -->
                        <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                            <a class="nav-link d-flex align-items-center {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0"/>
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/>
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/>
                                </svg>
                                <span class="nav-link-title">Home</span>
                            </a>
                        </li>
                        @foreach ($megamenus as $megaM)
                            @php
                                // Ambil sub-menu yang terkait dengan menu utama ini
                                $subMenus = $menus->where('parent_id', $megaM->id);
                            @endphp

                            @if ($subMenus->count() > 0) {{-- Hanya tampilkan menu utama jika ada sub-menu --}}
                                <li class="nav-item dropdown {{ request()->is($megaM->route . '/*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#navbar-base" 
                                    data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                                        {!! $megaM->icon !!}
                                        <span class="nav-link-title">{{ $megaM->name }}</span>
                                    </a>
                                    <ul class="dropdown-menu show">
                                        @foreach ($subMenus as $item)
                                            <li>
                                                <a class="dropdown-item {{ Route::is($item->route) ? 'active' : '' }}" 
                                                href="{{ route($item->route) }}" 
                                                {{ $item->name == 'API Documentation' ? 'target="_blank"' : '' }}>
                                                    {{ $item->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                        
                    </ul>
                </div>
            </div>
        </aside>
        
        <!-- Page content -->
        <div class="page-wrapper" style="margin-bottom: 100px">
            <div class="container p-3">
                <div class="navbar-nav d-flex g-5 flex-row justify-content-end ms-auto ">

                    <div class="d-none d-lg-flex">
                        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode"
                            data-bs-toggle="tooltip" data-bs-placement="bottom">
                            <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                                </svg>
                        </a>
                        <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode"
                            data-bs-toggle="tooltip" data-bs-placement="bottom">
                            <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path
                                    d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                </svg>
                        </a>
                        <div class="nav-item dropdown d-none d-md-flex me-3">
                            <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                aria-label="Show notifications">
                                <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                    <path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                                <span class="badge bg-red"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Last updates</h3>
                                    </div>
                                    <div class="list-group list-group-flush list-group-hoverable">
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span
                                                        class="status-dot status-dot-animated bg-red d-block"></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 1</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">
                                                        Change deprecated html tags to text decoration classes (#29604)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                            </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 2</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">
                                                        justify-content:between ⇒ justify-content:space-between (#29734)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions show">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow"
                                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                            </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 3</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">
                                                        Update change-version.js (#29736)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                            </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span
                                                        class="status-dot status-dot-animated bg-green d-block"></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <a href="#" class="text-body d-block">Example 4</a>
                                                    <div class="d-block text-secondary text-truncate mt-n1">
                                                        Regenerate package-lock.json (#29730)
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="list-group-item-actions">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                                            </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item dropdown d-none d-lg-flex">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                style="background-image: url('{{ asset('dist/img/logostatic.png') }}')"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ Auth::user()->name ?? 'Unknown' }}</div>
                                <div class="mt-1 small text-secondary">{{ $role->role_name }}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            
                            <a href="{{ route('setting-pengguna.index')}}" class="dropdown-item">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
            
                                <a href="{{ route('logout') }}" class="dropdown-item"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

