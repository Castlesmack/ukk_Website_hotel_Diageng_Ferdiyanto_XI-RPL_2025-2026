<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - UKK Villa</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        body {
            font-family: Instrument Sans, system-ui, Arial;
            padding: 20px;
            background: #f6f6f6
        }
    </style>
</head>

<body>
    <div style="display:flex;gap:20px;max-width:1100px;margin:0 auto">
        <aside style="width:220px;background:#fff;padding:18px;border:1px solid #e6e6e6">
            <h3>Ade Villa</h3>
            <nav style="margin-top:12px">
                <div style="padding:8px;background:#eee;margin-bottom:6px">Dashboard</div>
                <div style="padding:8px;margin-bottom:6px">Villas</div>
                <div style="padding:8px;margin-bottom:6px">Reservation</div>
                <div style="padding:8px;margin-bottom:6px">Users</div>
                <div style="padding:8px">Finance</div>
            </nav>
        </aside>

        <main style="flex:1;background:#fff;padding:18px;border:1px solid #e6e6e6">
            <header style="display:flex;justify-content:space-between;align-items:center">
                <h2>Dashboard</h2>
                <div style="width:40px;height:40px;border-radius:20px;background:#000"></div>
            </header>

            <section style="display:flex;gap:12px;margin-top:18px">
                <div style="flex:1;padding:12px;border:1px solid #e6e6e6">Total Villa<br><strong>7</strong></div>
                <div style="flex:1;padding:12px;border:1px solid #e6e6e6">Total
                    Pendapatan<br><strong>30,000,000</strong></div>
                <div style="flex:1;padding:12px;border:1px solid #e6e6e6">Total Tamu<br><strong>100</strong></div>
            </section>

            <section style="margin-top:18px">
                <h3>Grafik Penyewaan (preview)</h3>
                <div
                    style="height:200px;background:linear-gradient(#fff,#f2f2f2);border:1px solid #e6e6e6;padding:12px">
                    [Chart placeholder]</div>
            </section>
        </main>
    </div>
</body>

</html>