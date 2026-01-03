<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ade Villa')</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header.app {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header.app .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
        }

        header.app .nav {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        a.header-link {
            text-decoration: none;
            color: #007bff;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        a.header-link:hover {
            background: #f8f9fa;
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
            <a href="{{ $homeUrl }}" class="logo"><strong>Ade</strong> Villa</a>
            <div class="nav">
                @if(auth()->check())
                    @include('partials.profile_dropdown')
                @else
                    <a href="/login" class="header-link">Login</a>
                    <a href="/register" class="header-link">Register</a>
                @endif
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
</body>

</html>