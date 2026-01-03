<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Resepsionis - Reservation</title>
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

        .filters {
            display: flex;
            gap: 8px;
            margin-bottom: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            text-align: left
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
            <a href="/reception/reservations" style="background:#e6e6e6">Reservation</a>
            <a href="/reception/calendar">Calendar</a>
        </aside>
        <main class="main">
            <h3>Reservation</h3>
            <div class="card">
                <div class="filters">
                    <input type="text" placeholder="Itinerary ID/Name" style="padding:8px;flex:1">
                    <select style="padding:8px">
                        <option>Check-In</option>
                        <option>Check-Out</option>
                    </select>
                    <input type="date" style="padding:8px">
                    <input type="date" style="padding:8px">
                    <button style="padding:8px">GO</button>
                </div>
                <div style="overflow:auto">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Itinerary ID</th>
                                <th>No Telp</th>
                                <th>Guest Name</th>
                                <th>Booking Time</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Tipe</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>XXXX</td>
                                <td>081234567890</td>
                                <td>John doe</td>
                                <td>Sun, 10 Nov</td>
                                <td>Sat, 22 Nov</td>
                                <td>Sun, 23 Nov</td>
                                <td>0608</td>
                                <td>800,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>