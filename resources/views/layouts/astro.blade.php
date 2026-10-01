<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LSP Certification Portal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/astro-dark.css') }}">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div>
                <div class="brand">
                    <div class="brand-icon">▲</div>
                    <div class="brand-text">
                        <h1>LSP CERT</h1>
                        <span>JWD_2026</span>
                    </div>
                </div>

                <ul class="nav-menu">
                    <li>
                        <a href="{{ route('lsp.dashboard') }}" class="nav-link {{ request()->routeIs('lsp.dashboard') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('lsp.schemes') }}" class="nav-link {{ request()->routeIs('lsp.schemes') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                            </svg>
                            Skema Sertifikasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('lsp.participants') }}" class="nav-link {{ request()->routeIs('lsp.participants') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Peserta Sertifikasi
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="avatar">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</div>
                    <div>
                        <div style="font-size: 0.85rem; font-weight: 600;">{{ Auth::user()->name ?? 'Administrator' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">admin@lsp.id</div>
                    </div>
                </div>
                <form action="{{ route('lsp.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 0.6rem;" title="Logout">
                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="main-content">
            @if(session('success'))
                <div class="astro-card" style="border-color: rgba(16, 185, 129, 0.4); background: rgba(16, 185, 129, 0.05); padding: 0.85rem 1.25rem; margin-bottom: 1.5rem; color: var(--accent-emerald); font-weight: 500; font-size: 0.85rem;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="astro-card" style="border-color: rgba(244, 63, 94, 0.4); background: rgba(244, 63, 94, 0.05); padding: 0.85rem 1.25rem; margin-bottom: 1.5rem; color: var(--accent-rose); font-size: 0.85rem;">
                    @foreach($errors->all() as $err)
                        <div>• {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
