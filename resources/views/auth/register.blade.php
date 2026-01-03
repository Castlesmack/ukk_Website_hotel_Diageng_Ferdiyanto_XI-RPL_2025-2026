<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register - UKK Villa</title>
    <style>
        :root {
            --accent: #000;
            --muted: #e6e6e6
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
            margin: 0;
            padding: 0
        }

        .frame {
            max-width: 420px;
            margin: 24px auto;
            border: 1px solid var(--muted);
            border-radius: 8px;
            padding: 18px
        }

        header.top {
            height: 56px;
            background: #f6f6f6;
            border-bottom: 1px solid var(--muted);
            display: flex;
            align-items: center;
            padding: 0 12px
        }

        h1 {
            font-size: 20px;
            text-align: center;
            margin: 18px 0
        }

        label {
            display: block;
            margin-bottom: 6px
        }

        input[type=text],
        input[type=email],
        input[type=password] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: box-shadow .15s, transform .06s
        }

        input:focus {
            outline: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px)
        }

        .confirm {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: transform .12s, box-shadow .12s
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12)
        }

        .btn:active {
            transform: translateY(-1px)
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 12px;
            color: #666;
            text-decoration: none
        }
    </style>
</head>

<body>
    <div class="frame">
        <header class="top">
            <div style="width:34px;height:34px;border-radius:50%;background:#ddd"></div>
        </header>
        <h1>REGISTER</h1>
        <form method="POST" action="/register">
            @csrf
            <div>
                <label>Name*</label>
                <input type="text" name="name" required>
            </div>
            <div>
                <label>Email*</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>No Telp*</label>
                <input type="text" name="phone" required>
            </div>
            <div>
                <label>Password*</label>
                <input type="password" name="password" required>
            </div>
            <div class="confirm">
                <input type="checkbox" id="confirm" required>
                <label for="confirm" style="margin:0;font-size:13px">I confirm all the details I entered are
                    correct*</label>
            </div>
            <button class="btn" type="submit">Submit</button>
        </form>
    </div>
</body>

</html>