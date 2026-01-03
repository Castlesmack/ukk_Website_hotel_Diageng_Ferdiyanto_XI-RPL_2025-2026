@extends('layouts.app')

@section('title', 'Finances - Admin')

@push('styles')
    <style>
        .admin-layout {
            display: flex;
            gap: 20px;
            margin-top: 20px;
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
            color: white;
            text-decoration: none;
        }

        .sidebar .menu-item:hover,
        .sidebar .menu-item.active {
            background: #495057;
        }

        .main-content {
            flex: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .header h2 {
            margin: 0;
            color: #007bff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
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

        .positive {
            color: #28a745;
        }

        .negative {
            color: #dc3545;
        }

        .finance-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .finance-section h3 {
            margin-top: 0;
            color: #007bff;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f8f9fa;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .amount {
            font-weight: bold;
        }

        .income {
            color: #28a745;
        }

        .expense {
            color: #dc3545;
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
            margin-top: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="admin-layout">
        <aside class="sidebar">
            <h3>Ade Villa Admin</h3>
            <nav>
                <a href="/admin/dashboard" class="menu-item">Dashboard</a>
                <a href="/admin/villas" class="menu-item">Villas</a>
                <a href="/admin/reservations" class="menu-item">Reservations</a>
                <a href="/admin/users" class="menu-item">Users</a>
                <a href="/admin/finances" class="menu-item active">Finance</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header">
                <h2>Financial Overview</h2>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <strong class="positive">Rp 45,000,000</strong>
                </div>
                <div class="stat-card">
                    <h3>Total Expenses</h3>
                    <strong class="negative">Rp 12,000,000</strong>
                </div>
                <div class="stat-card">
                    <h3>Net Profit</h3>
                    <strong class="positive">Rp 33,000,000</strong>
                </div>
                <div class="stat-card">
                    <h3>Pending Payments</h3>
                    <strong>Rp 8,500,000</strong>
                </div>
            </div>

            <div class="finance-section">
                <h3>Revenue Chart</h3>
                <div class="chart-placeholder">
                    [Monthly Revenue Chart - Implement with Chart.js]
                </div>
            </div>

            <div class="finance-section">
                <h3>Recent Transactions</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-12-20</td>
                            <td>Villa Kota Bunga Ade - John Doe</td>
                            <td><span class="amount income">Income</span></td>
                            <td class="income">+Rp 5,104,000</td>
                        </tr>
                        <tr>
                            <td>2025-12-18</td>
                            <td>Maintenance - Villa Puncak Harmony</td>
                            <td><span class="amount expense">Expense</span></td>
                            <td class="expense">-Rp 2,500,000</td>
                        </tr>
                        <tr>
                            <td>2025-12-15</td>
                            <td>Villa Sunset Paradise - Jane Smith</td>
                            <td><span class="amount income">Income</span></td>
                            <td class="income">+Rp 8,500,000</td>
                        </tr>
                        <tr>
                            <td>2025-12-10</td>
                            <td>Utilities - December</td>
                            <td><span class="amount expense">Expense</span></td>
                            <td class="expense">-Rp 1,200,000</td>
                        </tr>
                        <tr>
                            <td>2025-12-08</td>
                            <td>Villa Harmony View - Mike Johnson</td>
                            <td><span class="amount income">Income</span></td>
                            <td class="income">+Rp 7,200,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection