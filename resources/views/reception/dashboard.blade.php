<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Resepsionis - Dashboard</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff
        }

        .container {
            max-width: 1200px;
            margin: 18px auto;
            display: flex;
            gap: 18px
        }

        .sidebar {
            width: 220px;
            border-right: 2px solid #eee;
            padding: 18px
        }

        .main {
            flex: 1;
            padding: 18px
        }

        .card {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 12px
        }

        .kpis {
            display: flex;
            gap: 12px
        }

        .kpi {
            flex: 1;
            border: 1px solid #ddd;
            padding: 18px;
            border-radius: 8px;
            text-align: center
        }
    </style>
</head>

<body>
    <header
        style="display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto;padding:12px 0">
        <h2>Ade Villa — Resepsionis</h2>
        <div style="width:36px;height:36px;background:#000;border-radius:50%"></div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <a href="/reception/dashboard" style="background:#e6e6e6">Dashboard</a>
            <a href="/reception/reservations">Reservation</a>
            <a href="/reception/calendar">Calendar</a>
        </aside>
        <main class="main">
            <div class="kpis">
                <div class="kpi">
                    <h1>100</h1>
                    <div>Total Tamu</div>
                </div>
                <div class="kpi">
                    <h1>30</h1>
                    <div>Total Pemasukan</div>
                </div>
                <div class="kpi">
                    <h3>Jadwal Tamu</h3>
                    <div style="text-align:left">- Arrgh<br>- Arrgh</div>
                </div>
            </div>
            <div class="card">
                <h4>Penyewaan Minggu ini</h4>
                <div
                    style="height:160px;background:#fafafa;border:1px dashed #ddd;margin-top:12px;display:flex;align-items:center;justify-content:center">
                    Chart placeholder</div>
            </div>
        </main>
    </div>
</body>

</html>