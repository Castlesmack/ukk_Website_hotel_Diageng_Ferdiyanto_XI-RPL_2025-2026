@extends('layouts.app')

@section('title', 'Reservations - Admin')

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

        .reservation-table {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }

        .reservation-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .reservation-table th,
        .reservation-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .reservation-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .reservation-table tr:hover {
            background: #f8f9fa;
        }

        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status.confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
        }

        .view-btn {
            background: #007bff;
            color: white;
        }

        .view-btn:hover {
            background: #0056b3;
        }

        .edit-btn {
            background: #28a745;
            color: white;
        }

        .edit-btn:hover {
            background: #218838;
        }
    </style>
@endpush

@section('content')
    <div class="admin-layout">
        <aside class="sidebar">
            <h3>Ade Villa Admin</h3>
            <a href="/admin/dashboard" class="menu-item">Dashboard</a>
            <a href="/admin/villas" class="menu-item">Villas</a>
            <a href="/admin/reservations" class="menu-item active">Reservations</a>
            <a href="/admin/users" class="menu-item">Users</a>
            <a href="/admin/finances" class="menu-item">Finance</a>
        </aside>

        <main class="main-content">
            <div class="header">
                <h2>Reservations</h2>
            </div>

            <div class="reservation-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guest</th>
                            <th>Villa</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>14524621</td>
                            <td>John Doe</td>
                            <td>Villa Kota Bunga Ade</td>
                            <td>2025-12-20</td>
                            <td>2025-12-21</td>
                            <td><span class="status confirmed">Confirmed</span></td>
                            <td class="actions">
                                <a href="#" class="action-btn view-btn">View</a>
                                <a href="#" class="action-btn edit-btn">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td>14524622</td>
                            <td>Jane Smith</td>
                            <td>Villa Puncak Harmony</td>
                            <td>2026-01-15</td>
                            <td>2026-01-18</td>
                            <td><span class="status pending">Pending</span></td>
                            <td class="actions">
                                <a href="#" class="action-btn view-btn">View</a>
                                <a href="#" class="action-btn edit-btn">Edit</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection