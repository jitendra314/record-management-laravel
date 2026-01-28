<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <style>
        body {
            min-height: 100vh;
            background: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #212529;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: .6rem .75rem;
            border-radius: .375rem;
        }

        .sidebar a.active,
        .sidebar a:hover {
            color: #fff;
            background: #343a40;
        }

        /* Mobile behavior */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1050;
                left: -250px;
                top: 0;
                transition: left .3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .content-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .4);
                z-index: 1040;
            }

            .content-overlay.show {
                display: block;
            }

            main {
                padding: 1rem !important;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex">

        @auth
            {{-- Sidebar --}}
            <aside class="sidebar p-3" id="sidebar">
                <h5 class="text-white mb-4">Admin Panel</h5>

                <ul class="nav nav-pills flex-column gap-1">

                    <li class="nav-item">
                        <a href="{{ route('products.index') }}"
                            class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            Products
                        </a>
                    </li>

                    @can('viewAny', App\Models\Role::class)
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                                class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                Roles
                            </a>
                        </li>
                    @endcan

                    @can('viewAny', App\Models\User::class)
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                Users
                            </a>
                        </li>
                    @endcan

                </ul>
            </aside>
        @endauth

        {{-- Overlay (mobile only) --}}
        <div class="content-overlay" id="overlay"></div>

        {{-- Main --}}
        <div class="flex-grow-1">

            {{-- Top bar --}}
            <nav class="navbar navbar-light bg-white border-bottom px-3 px-md-4">
                <div class="d-flex align-items-center gap-2">

                    {{-- Mobile sidebar toggle --}}
                    @auth
                        <button class="btn btn-light border d-md-none" id="sidebarToggle" aria-label="Toggle menu">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M2 12.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                            </svg>
                        </button>
                    @endauth

                    {{-- Page title (optional) --}}
                    <span class="fw-semibold text-secondary d-none d-md-inline">
                        @yield('title')
                    </span>
                </div>

                @auth
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">

                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 34px; height: 34px; font-size: 14px;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <span class="d-none d-md-inline fw-medium text-dark">
                                {{ auth()->user()->name }}
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2">
                            <li class="px-3 py-2 text-muted small">
                                Signed in as<br>
                                <strong>{{ auth()->user()->email }}</strong>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </nav>

            <main class="p-3 p-md-4">
                @yield('content')
            </main>

        </div>
    </div>

    {{-- Toasts --}}
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
        @foreach (['success', 'error'] as $type)
            @if (session($type))
                <div
                    class="toast align-items-center text-bg-{{ $type === 'success' ? 'success' : 'danger' }} border-0 show">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session($type) }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toast init
        document.querySelectorAll('.toast').forEach(el => {
            new bootstrap.Toast(el, {
                delay: 3000
            }).show();
        });

        // Sidebar toggle (mobile)
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggle = document.getElementById('sidebarToggle');

        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    </script>

</body>

</html>
