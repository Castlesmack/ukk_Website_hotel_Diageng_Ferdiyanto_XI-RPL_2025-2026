<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Finances</title>
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
            gap: 12px;
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
        <h2>Ade Villa — Admin</h2>
        <div style="width:36px;height:36px;background:#000;border-radius:50%"></div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <a href="/admin/dashboard">Dashboard</a>
            <a href="/admin/villas/create">Villas</a>
            <a href="/admin/reservations">Reservation</a>
            <a href="/admin/users">Users</a>
            <a href="/admin/finances" style="background:#e6e6e6">Finance</a>
        </aside>
        <main class="main">
            <h3>Finance</h3>
            <div class="card">
                <div class="filters">
                    <div><label>Date range</label><br><input type="text" placeholder="Start - End"
                            style="padding:8px;width:220px"></div>
                    <div><label>Villa Type</label><br><select style="padding:8px">
                            <option>All</option>
                            <option>Type A</option>
                        </select></div>
                    <div style="flex:1"><label>Search</label><br><input type="search" style="padding:8px;width:100%"
                            placeholder="Search"></div>
                </div>
                <div style="margin-top:12px;overflow:auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Villa Name</th>
                                <th>Booking ID</th>
                                <th>Guest Name</th>
                                <th>Status</th>
                                <th>Income</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sun, 10 Nov, 2025</td>
                                <td>0608</td>
                                <td>XXXX</td>
                                <td>John doe</td>
                                <td>Paid</td>
                                <td>600,000.00 Rp</td>
                            </tr>
                            <tr>
                                <td>Sun, 10 Nov, 2025</td>
                                <td>0609</td>
                                <td>XXXX</td>
                                <td>Jane doe</td>
                                <td>Paid</td>
                                <td>500,000.00 Rp</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>