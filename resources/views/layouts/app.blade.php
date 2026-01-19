<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Ade Villa')</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: #FAF2E8;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header.app {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            padding: 15px 20px;
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header.app .logo {
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        header.app .logo img {
            height: 45px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        header.app .nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
        }

        .nav-center {
            display: flex;
            gap: 40px;
            list-style: none;
            margin: 0;
        }

        .nav-auth {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-center a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-center a:hover {
            color: #666;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #4A90E2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            color: white;
            font-weight: 600;
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 20px;
            background: #FAF2E8;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 150px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 2000;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            width: 100%;
            padding: 12px 16px;
            text-align: left;
            border: none;
            background: none;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            font-size: 13px;
            transition: background 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-menu a:last-child,
        .dropdown-menu button:last-child {
            border-bottom: none;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background: #f5f5f5;
        }

        .dropdown-menu button {
            font-family: inherit;
            color: #e74c3c;
        }

        .dropdown-menu button:hover {
            background: #ffe0e0;
        }

        a.header-link {
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 4px;
            transition: all 0.3s;
            font-size: 14px;
        }

        a.header-link:first-child {
            color: #333;
            border: 1px solid #333;
        }

        a.header-link:first-child:hover {
            background: #f5f5f5;
        }

        a.header-link:last-child {
            background: #333;
            color: white;
        }

        a.header-link:last-child:hover {
            background: #555;
        }

        main.container {
            flex: 1;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        footer {
            background: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            header.app {
                flex-direction: column;
                gap: 10px;
            }

            .container {
                padding: 0 10px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @if(!isset($hideHeader) || !$hideHeader)
        <header class="app">
            @php
                $homeUrl = '/';
                if (auth()->check()) {
                    $user = auth()->user();
                    if ($user->role === 'admin') {
                        $homeUrl = '/admin/dashboard';
                    } elseif ($user->role === 'receptionist') {
                        $homeUrl = '/reception/dashboard';
                    }
                }
            @endphp
            <a href="{{ $homeUrl }}" class="logo">
                <img src="{{ asset('logo.png') }}" alt="Ade Villa">
            </a>
            <div class="nav">
            </div>
            <div class="nav-right">
                @auth
                    @php
                        $user = auth()->user();
                        $initials = '';
                        $names = explode(' ', $user->name);
                        if (count($names) >= 2) {
                            $initials = strtoupper($names[0][0] . $names[count($names) - 1][0]);
                        } else {
                            $initials = strtoupper(substr($names[0], 0, 2));
                        }
                    @endphp
                    <div class="user-avatar" onclick="toggleUserDropdown(event)">{{ $initials }}</div>
                    <div class="dropdown-menu" id="userDropdown">
                        @if (auth()->user()->role === 'user' || auth()->user()->role === 'guest')
                            <a href="{{ route('user.profile') }}">Profile</a>
                            <a href="{{ route('user.bookings') }}">Bookings</a>
                        @endif
                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'receptionist')
                            <a href="{{ route('user.profile') }}">Profile</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="nav-auth">
                        <a href="/login" class="header-link">Login</a>
                        <a href="/register" class="header-link">Register</a>
                    </div>
                @endauth
            </div>
        </header>
    @endif

    <main class="container">
        @yield('content')
    </main>

    @if(!isset($hideFooter) || !$hideFooter)
        <footer>
            <p>&copy; 2026 Ade Villa. All rights reserved.</p>
        </footer>
    @endif

    @stack('scripts')
    <script>
        function toggleUserDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.classList.toggle('active');
            }
        }

        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('userDropdown');
            const userAvatar = document.querySelector('.user-avatar');
            if (dropdown && userAvatar && !userAvatar.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>
</body>

</html>