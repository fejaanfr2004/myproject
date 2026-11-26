
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard | Luxury Fashion Hub')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gold: #d4af72;
            --gold-light: #f0d39e;
            --panel: #171717;
            --border: #302b23;
            --muted: #999;
            --background: #0d0d0d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: white;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #141414;
            border-right: 1px solid var(--border);
            padding: 28px 18px;
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }

        .brand {
            color: var(--gold);
            font-size: 19px;
            font-weight: 800;
            padding: 5px 10px 35px;
        }

        .brand span {
            display: block;
            color: #888;
            font-size: 10px;
            letter-spacing: 4px;
            margin-top: 5px;
        }

        .nav-label {
            color: #666;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 15px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #aaa;
            text-decoration: none;
            padding: 14px 13px;
            margin-bottom: 5px;
            border-radius: 7px;
            font-size: 13px;
            transition: .2s;
            border: 0;
            background: transparent;
            width: 100%;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(212,175,114,.13);
            color: var(--gold-light);
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-bottom {
            margin-top: auto;
            border-top: 1px solid var(--border);
            padding-top: 18px;
        }

        .main {
            margin-left: 260px;
            min-height: 100vh;
            padding: 32px 38px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 700;
        }

        .page-subtitle {
            color: var(--muted);
            font-size: 12px;
            margin-top: 8px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--panel);
            border: 1px solid var(--border);
            padding: 10px 15px;
            border-radius: 9px;
        }
        .admin-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--panel);
    border: 1px solid var(--border);
    padding: 10px 15px;
    border-radius: 9px;
}

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--gold);
            color: #111;
            font-weight: 800;
        }

        .profile-name {
            font-size: 12px;
            font-weight: 700;
        }

        .profile-role {
            color: var(--gold);
            font-size: 10px;
            margin-top: 4px;
            text-transform: uppercase;
        }

        #admin-content {
            min-height: 70vh;
        }

        #admin-content.loading {
            opacity: .55;
            pointer-events: none;
        }

        .admin-loading {
            color: var(--gold);
            padding: 25px;
            text-align: center;
        }

        .admin-footer {
            margin-top: 35px;
            padding: 20px 0;
            border-top: 1px solid var(--border);
            color: #666;
            font-size: 10px;
            text-align: center;
        }

        @media(max-width: 850px) {
            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
                padding: 25px 20px;
            }
        }

        @media(max-width: 650px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar-bottom {
                margin-top: 15px;
            }

            .main {
                margin-left: 0;
                padding: 22px 16px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<aside class="sidebar">

    <div class="brand">
        LUXURY FASHION
        <span>H U B</span>
    </div>

    <div class="nav-label">MAIN MENU</div>

    {{-- DASHBOARD --}}
    <a href="{{ route('admin.dashboard') }}"
       class="nav-link"
       data-admin-link>
        <span class="nav-icon">▦</span> Dashboard
    </a>

    {{-- PRODUCT MANAGEMENT --}}
    @can('view-products')
        <a href="{{ route('admin.products.index') }}"
           class="nav-link"
           data-admin-link>
            <span class="nav-icon">◈</span> Product Management
        </a>
    @endcan

    {{-- VISIT STORE PRODUCTS --}}
    <a href="{{ route('products.index') }}" class="nav-link">
        <span class="nav-icon">◇</span> Visit Store Products
    </a>

    <div class="nav-label">STORE MANAGEMENT</div>

    {{-- USER MANAGEMENT --}}
    @can('manage-users')
        <a href="{{ route('admin.users.index') }}"
           class="nav-link"
           data-admin-link>
            <span class="nav-icon">♙</span> User Management
        </a>
    @endcan

    {{-- ROLE AND PERMISSION MANAGEMENT --}}
    @can('manage-roles')
        <a href="{{ route('admin.roles.index') }}"
           class="nav-link"
           data-admin-link>
            <span class="nav-icon">♛</span> Roles & Permissions
        </a>
    @endcan

    {{-- ORDER MANAGEMENT --}}
    @can('view-orders')
        <a href="{{ route('admin.orders.index') }}"
           class="nav-link"
           data-admin-link>
            <span class="nav-icon">▣</span> Orders
        </a>
    @endcan

    {{-- REPORTS MANAGEMENT --}}
    @can('view-orders')
        <a href="{{ route('admin.reports.index') }}"
           class="nav-link"
           data-admin-link>
            <span class="nav-icon">▥</span> Reports
        </a>
    @endcan
      {{-- ACTIVITY LOGS --}}
@role('Super Admin')
    <a href="{{ route('admin.activity-logs.index') }}"
       class="nav-link"
       data-admin-link>
        <span class="nav-icon">◷</span> Activity Logs
    </a>
@endrole
    {{-- CATEGORY MANAGEMENT --}}
    @can('manage-categories')
        <a href="{{ route('admin.categories.index') }}"
           class="nav-link"
           data-admin-link>
            <span class="nav-icon">▤</span> Categories
        </a>
    @endcan

    {{-- SIDEBAR BOTTOM --}}
    <div class="sidebar-bottom">

        <a href="{{ route('home') }}" class="nav-link">
            <span class="nav-icon">↗</span> Visit Website
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="nav-link">
                <span class="nav-icon">⇥</span> Logout
            </button>
        </form>

    </div>

</aside>

<main class="main">

    {{-- TOP HEADER --}}
    <header class="topbar">

        <div>
            <h1 class="page-title">Luxury Fashion Hub</h1>
            <p class="page-subtitle">Your store control center</p>
        </div>

       <div class="topbar-actions">

    {{-- NOTIFICATION BELL --}}

    <a href="{{ route('admin.notifications.index') }}"
       class="notification-bell"
       data-admin-link
       title="Notifications">

        🔔

        @php
            $unreadCount = auth()->user()->unreadNotifications()->count();
        @endphp

        @if($unreadCount > 0)
            <span class="notification-count">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif

    </a>

    {{-- ADMIN PROFILE --}}

    <div class="admin-profile">

        <div class="avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div>
            <div class="profile-name">
                {{ auth()->user()->name }}
            </div>

            <div class="profile-role">
                {{ auth()->user()->getRoleNames()->first() ?? 'USER' }}
            </div>
        </div>

    </div>

</div>
    </header>

    {{-- DYNAMIC PAGE CONTENT --}}
    <div id="admin-content">
        @yield('admin-content')
    </div>

    {{-- FOOTER --}}
    <footer class="admin-footer">
        © {{ date('Y') }} LUXURY FASHION HUB. ALL RIGHTS RESERVED.
    </footer>

</main>

<script>
(function () {

    const content = document.getElementById('admin-content');

    if (!content) return;

    let currentRequest = null;

    // Highlight active sidebar link
    function setActiveLink(url) {

        document.querySelectorAll('[data-admin-link]').forEach(link => {

            const linkUrl = new URL(link.href, window.location.origin);
            const currentUrl = new URL(url, window.location.origin);

            link.classList.toggle(
                'active',
                linkUrl.pathname === currentUrl.pathname
            );

        });

    }

    // Update page content without full page reload
    function updatePage(html, url) {

        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const newContent = doc.getElementById('admin-content');

        if (!newContent) {
            window.location.href = url;
            return;
        }

        // Remove old page-specific styles
        document.querySelectorAll('[data-admin-dynamic-style]')
            .forEach(style => style.remove());

        // Load new page-specific styles
        doc.head.querySelectorAll('style').forEach(oldStyle => {

            const newStyle = document.createElement('style');

            newStyle.dataset.adminDynamicStyle = 'true';
            newStyle.textContent = oldStyle.textContent;

            document.head.appendChild(newStyle);

        });

        // Replace only the content area
        content.innerHTML = newContent.innerHTML;

        // Update browser title
        document.title = doc.title || 'Admin Dashboard | Luxury Fashion Hub';

        // Highlight active sidebar link
        setActiveLink(url);

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    }

    // Load page using AJAX
    async function loadPage(url, pushHistory = true) {

        if (currentRequest) {
            currentRequest.abort();
        }

        const request = new AbortController();
        currentRequest = request;

        content.classList.add('loading');
        content.innerHTML = '<div class="admin-loading">Loading...</div>';

        try {

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                },
                signal: request.signal
            });

            if (!response.ok) {
                throw new Error('Unable to load page');
            }

            const html = await response.text();

            if (currentRequest !== request) return;

            updatePage(html, response.url);

            if (pushHistory) {
                history.pushState({}, '', response.url);
            }

        } catch (error) {

            if (error.name !== 'AbortError') {
                window.location.href = url;
            }

        } finally {

            if (currentRequest === request) {
                content.classList.remove('loading');
                currentRequest = null;
            }

        }

    }

    // Sidebar and content navigation
    document.addEventListener('click', function (event) {

        const link = event.target.closest('a');

        if (!link) return;

        if (
            !content.contains(link) &&
            !link.hasAttribute('data-admin-link')
        ) {
            return;
        }

        if (
            link.target === '_blank' ||
            link.hasAttribute('download') ||
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey
        ) {
            return;
        }

        const url = new URL(link.href, window.location.origin);

        if (url.origin !== window.location.origin) return;

        event.preventDefault();

        loadPage(url.href);

    });

    // AJAX form submission
    document.addEventListener('submit', async function (event) {

        const form = event.target;

        if (!content.contains(form)) return;

        event.preventDefault();

        const method = (
            form.querySelector('input[name="_method"]')?.value ||
            form.method ||
            'GET'
        ).toUpperCase();

        const formData = new FormData(form);

        let url = form.action;

        // Search forms
        if (method === 'GET') {

            const params = new URLSearchParams(formData);

            url += (url.includes('?') ? '&' : '?') + params.toString();

            loadPage(url);

            return;
        }

        // Delete confirmation
        if (
            form.dataset.confirm &&
            !confirm(form.dataset.confirm)
        ) {
            return;
        }

        content.classList.add('loading');

        try {

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) {
                throw new Error('Form submission failed');
            }

            const html = await response.text();

            updatePage(html, response.url);

            history.pushState({}, '', response.url);

        } catch (error) {

            alert('Something went wrong. Please try again.');

        } finally {

            content.classList.remove('loading');

        }

    });

    // Browser back and forward navigation
    window.addEventListener('popstate', function () {

        loadPage(window.location.href, false);

    });

    // Set active sidebar link on initial page load
    setActiveLink(window.location.href);

})();
</script>

@stack('scripts')

</body>
</html>
