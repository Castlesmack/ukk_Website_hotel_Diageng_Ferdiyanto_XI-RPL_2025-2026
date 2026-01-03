<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Ade Villa')</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            background: #fff
        }

        header.app {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background: #f6f6f6;
            border-bottom: 1px solid #eee
        }

        a.header-link {
            margin-left: 8px;
            text-decoration: none;
            color: #222
        }

        .container {
            max-width: 1000px;
            margin: 18px auto;
            padding: 0 12px
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="app">
        <div><strong>Ade</strong> Villa</div>
        <div>
            @if(auth()->check())
                @include('partials.profile_dropdown')
            @else
                <a href="/login" class="header-link">Login</a>
                <a href="/register" class="header-link">Register</a>
            @endif
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>