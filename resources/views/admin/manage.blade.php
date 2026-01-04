@extends('layouts.app')

@section('title', 'Manage Villas - Admin')

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
            height: fit-content;
            flex-shrink: 0;
        }

        .sidebar h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #fff;
            font-size: 18px;
            border-bottom: 2px solid #495057;
            padding-bottom: 15px;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar .menu-item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            color: #333;
            text-decoration: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            background: #e9ecef;
            text-align: center;
        }

        .sidebar .menu-item:hover {
            background: #007bff;
            color: white;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .sidebar .menu-item.active {
            background: #007bff;
            color: white;
            font-weight: 600;
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
            color: #333;
        }

        .add-btn {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .add-btn:hover {
            background: #218838;
        }

        .villa-list {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
        }

        .villa-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 10px;
            background: #f8f9fa;
        }

        .villa-info h4 {
            margin: 0 0 5px;
            color: #007bff;
        }

        .villa-info p {
            margin: 0;
            color: #6c757d;
        }

        .villa-actions {
            display: flex;
            gap: 10px;
        }

        .edit-btn {
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .edit-btn:hover {
            background: #0056b3;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .delete-btn:hover {
            background: #c82333;
        }
    </style>
@endpush

@section('content')
    <div class="admin-layout">
        <aside class="sidebar">
            <h3>Ade Villa Admin</h3>
            <nav>
                <a href="/admin/dashboard" class="menu-item">Dashboard</a>
                <a href="/admin/manage" class="menu-item active">Manage</a>
                <a href="/admin/reservations" class="menu-item">Reservations</a>
                <a href="/admin/users" class="menu-item">Users</a>
                <a href="/admin/finances" class="menu-item">Finance</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header">
                <h2>Manage Villas</h2>
                <a href="/admin/villas/create" class="add-btn">Add New Villa</a>
            </div>

            <div class="villa-list">
                <div class="villa-item">
                    <div class="villa-info">
                        <h4>Villa Kota Bunga Ade</h4>
                        <p>Comfortable villa close to the city • Rp 5,104,000 / night</p>
                    </div>
                    <div class="villa-actions">
                        <a href="/admin/villas/1/edit" class="edit-btn">Edit</a>
                        <a href="#" class="delete-btn" onclick="return confirm('Delete this villa?')">Delete</a>
                    </div>
                </div>

                <div class="villa-item">
                    <div class="villa-info">
                        <h4>Villa Puncak Harmony</h4>
                        <p>Spacious villa with panoramic mountain views • Rp 7,200,000 / night</p>
                    </div>
                    <div class="villa-actions">
                        <a href="/admin/villas/2/edit" class="edit-btn">Edit</a>
                        <a href="#" class="delete-btn" onclick="return confirm('Delete this villa?')">Delete</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection