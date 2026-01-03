@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
    <style>
        .admin-layout {
            display: flex;
            gap: 20px;
        }

        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            padding: 20px;
            border-radius: 8px;
        }

        .sidebar h3 {
            margin-top: 0;
            color: #fff;
        }

        .sidebar .menu-item {
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .sidebar .menu-item:hover,
        .sidebar .menu-item.active {
            background: #495057;
        }

        .main-content {
            flex: 1;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            margin: 0 0 10px;
            color: #007bff;
        }

        .stat-card strong {
            font-size: 24px;
            color: #333;
        }

        .chart-placeholder {
            height: 300px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="admin-layout">
        <aside class="sidebar">
            <h3>Ade Villa Admin</h3>
            <nav>
                <div class="menu-item active">Dashboard</div>
                <a href="/admin/villas" class="menu-item" style="color: white; text-decoration: none;">Villas</a>
                <a href="/admin/reservations" class="menu-item"
                    style="color: white; text-decoration: none;">Reservations</a>
                <a href="/admin/users" class="menu-item" style="color: white; text-decoration: none;">Users</a>
                <a href="/admin/finances" class="menu-item" style="color: white; text-decoration: none;">Finance</a>
            </nav>
        </aside>

        <main class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Dashboard</h2>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #007bff;"></div>
            </header>

            <section class="stats">
                <div class="stat-card">
                    <h3>Total Villas</h3>
                    <strong>7</strong>
                </div>
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <strong>Rp 30,000,000</strong>
                </div>
                <div class="stat-card">
                    <h3>Total Guests</h3>
                    <strong>100</strong>
                </div>
            </section>

            <section>
                <h3>Rental Chart</h3>
                <div class="chart-placeholder">
                    [Chart Placeholder - Implement with Chart.js or similar]
                </div>
            </section>
        </main>
    </div>
@endsection
</div>
</body>

</html>