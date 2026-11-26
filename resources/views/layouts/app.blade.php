
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Luxury Fashion Hub')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f7f5;
            color: #171717;
        }

        header {
            background: #111;
            color: white;
            padding: 20px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo {
            color: #c9a96e;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 3px;
            text-decoration: none;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 22px;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }

        nav a:hover {
            color: #c9a96e;
        }

        nav form {
            margin: 0;
        }

        nav button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
        }

        nav button:hover {
            color: #c9a96e;
        }

        .page-content {
            min-height: 70vh;
        }

        footer {
            background: #111;
            color: #ccc;
            text-align: center;
            padding: 25px 15px;
            margin-top: 50px;
        }

        .flash-message {
            max-width: 1100px;
            margin: 20px auto;
            padding: 14px;
            background: #e7f5e9;
            color: #205b2b;
        }

        .error-message {
            max-width: 1100px;
            margin: 20px auto;
            padding: 14px;
            background: #ffe8e8;
            color: #a83232;
        }
/* CUSTOMER NOTIFICATION BELL */

.customer-notification {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: white;
    text-decoration: none;
    font-size: 18px;
}

.customer-notification:hover {
    color: #c9a96e;
}

.customer-notification-count {
    position: absolute;
    top: -10px;
    right: -12px;
    background: #dc3545;
    color: white;
    font-size: 10px;
    font-weight: bold;
    min-width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2px;
}
        @media(max-width: 650px) {
            header {
                justify-content: center;
                text-align: center;
            }

            nav {
                justify-content: center;
                gap: 14px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<header>
    <a href="{{ route('home') }}" class="logo">
        LUXURY FASHION
    </a>

    <nav>
        <a href="{{ route('home') }}">HOME</a>

        <a href="{{ route('products.index') }}">PRODUCTS</a>

        <a href="{{ route('cart.index') }}">
            CART ({{ $cartCount ?? array_sum(array_column(session('cart', []), 'quantity')) }})
        </a>
@auth
    <a href="{{ route('notifications.index') }}"
       class="customer-notification"
       title="My Notifications">

        🔔

        @php
            $customerUnreadCount = auth()->user()->unreadNotifications()->count();
        @endphp

        @if($customerUnreadCount > 0)
            <span class="customer-notification-count">
                {{ $customerUnreadCount > 99 ? '99+' : $customerUnreadCount }}
            </span>
        @endif

    </a>
@endauth
        @auth
            @can('view-admin-dashboard')
                <a href="{{ route('admin.dashboard') }}">ADMIN</a>
            @endcan

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">LOGOUT</button>
            </form>
        @else
            <a href="{{ route('login') }}">LOGIN</a>
            <a href="{{ route('register') }}">REGISTER</a>
        @endauth
    </nav>
</header>

@if(session('success'))
    <div class="flash-message">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="error-message">
        {{ session('error') }}
    </div>
@endif

<main class="page-content">
    @yield('content')
</main>

<footer>
    <p>© {{ date('Y') }} Luxury Fashion Hub. All Rights Reserved.</p>
</footer>

@stack('scripts')

</body>
</html>
