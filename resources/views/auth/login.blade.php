<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - UKK Villa</title>
    <style>
        body {
            font-family: Instrument Sans, Arial;
            padding: 30px;
            background: #f9f9f9
        }
    </style>
</head>

<body>
    <div
        style="max-width:420px;margin:24px auto;border:1px solid #e6e6e6;border-radius:8px;padding:18px;background:#fff">

        <h1 style="text-align:center;margin:18px 0">Login</h1>
        @if($errors->any())
            <div style="color:#c62828;margin-bottom:8px;text-align:center">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/login">
            @csrf
            <div style="margin-bottom:12px">
                <label>Email*</label>
                <input type="email" name="email"
                    style="width:100%;padding:12px;border:2px solid #ddd;border-radius:8px">
            </div>
            <div style="margin-bottom:12px">
                <label>Password*</label>
                <input type="password" name="password"
                    style="width:100%;padding:12px;border:2px solid #ddd;border-radius:8px">
            </div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                <input type="checkbox" id="confirm-login" required>
                <label for="confirm-login" style="margin:0;font-size:13px">I confirm all the details I entered are
                    correct*</label>
            </div>
            <button
                style="display:block;width:100%;padding:14px;background:#000;color:#fff;border:none;border-radius:8px;font-size:18px">Submit</button>
        </form>
        <p style="text-align:center;margin-top:12px"><a href="/password/reset">Forgot password</a></p>
    </div>
</body>

</html>