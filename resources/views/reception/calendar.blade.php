<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Resepsionis - Calendar</title>
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
            padding: 12px
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px
        }

        .day {
            min-height: 80px;
            border: 1px solid #eee;
            padding: 8px
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
            <a href="/reception/dashboard">Dashboard</a>
            <a href="/reception/reservations">Reservation</a>
            <a href="/reception/calendar" style="background:#e6e6e6">Calendar</a>
        </aside>
        <main class="main">
            <h3>Availability List</h3>
            <div class="card">
                <div style="display:flex;justify-content:space-between;margin-bottom:12px">
                    <div>
                        <select>
                            <option>November 2025</option>
                        </select>
                        <button>Today</button>
                    </div>
                    <div>
                        <select>
                            <option>Type 0608</option>
                        </select>
                    </div>
                </div>
                <div class="calendar-grid">
                    <div class="day">Sun<br><small>0/0</small></div>
                    <div class="day">Mon<br><small>0/0</small></div>
                    <div class="day">Tue<br><small>0/0</small></div>
                    <div class="day">Wed<br><small>0/0</small></div>
                    <div class="day">Thu<br><small>0/0</small></div>
                    <div class="day">Fri<br><small>0/0</small></div>
                    <div class="day">Sat<br><small>0/0</small></div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>