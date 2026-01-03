<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reset Password - UKK Villa</title>
    <style>
        body {
            font-family: Instrument Sans, Arial;
            padding: 30px;
            background: #f9f9f9
        }
    </style>
</head>

<body>
    <div class="frame"
        style="max-width:420px;margin:24px auto;border:1px solid #e6e6e6;border-radius:8px;padding:18px;background:#fff">
        <header
            style="height:56px;background:#f6f6f6;border-bottom:1px solid #e6e6e6;display:flex;align-items:center;padding:0 12px">
            <div style="width:34px;height:34px;border-radius:50%;background:#ddd"></div>
        </header>
        <h1 style="text-align:center;margin:18px 0">Reset Your Password</h1>
        <p style="text-align:center;color:#555">Enter the email associated with your account and we'll send you a link
            to reset your password.</p>
        <form method="POST" action="/password/reset">
            @csrf
            <div style="margin:12px 0">
                <input type="email" name="email" placeholder="Email" required
                    style="width:100%;padding:12px;border:2px solid #ddd;border-radius:8px">
            </div>
            <div>
                <button
                    style="display:block;width:100%;padding:14px;background:#000;color:#fff;border:none;border-radius:8px;font-size:18px">Submit</button>
            </div>
        </form>
        <p style="text-align:center;margin-top:12px"><a href="/login">Back to Login</a></p>
    </div>
</body>

</html>