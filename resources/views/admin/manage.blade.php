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
            position: sticky;
            top: 100px;
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
            margin-bottom: 30px;
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
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .add-btn:hover {
            background: #218838;
        }

        .villa-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .villa-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .villa-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .villa-card-header {
            padding: 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .villa-card-header h3 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 16px;
        }

        .villa-info {
            padding: 15px;
        }

        .villa-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .villa-info-row label {
            font-weight: 600;
            color: #666;
        }

        .villa-info-row value {
            color: #333;
        }

        .villa-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .villa-status.active {
            background: #d4edda;
            color: #155724;
        }

        .villa-status.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .villa-status.maintenance {
            background: #fff3cd;
            color: #856404;
        }

        .villa-card-footer {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-small {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-edit {
            background: #007bff;
            color: white;
        }

        .btn-edit:hover {
            background: #0056b3;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 8px;
            color: #666;
        }

        .empty-state h3 {
            margin: 0 0 10px 0;
        }
    </style>
@endpush

<div class="admin-layout">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin Menu</h3>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="menu-item">Dashboard</a>
            <a href="{{ route('admin.villas.index') }}" class="menu-item active">Manage Villas</a>
            <a href="{{ route('admin.reservations') }}" class="menu-item">Reservations</a>
            <a href="{{ route('admin.users.index') }}" class="menu-item">Users</a>
            <a href="{{ route('admin.homepage.edit') }}" class="menu-item">Edit Homepage</a>
            <a href="{{ route('admin.finances') }}" class="menu-item">Finances</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Manage Villas</h2>
            <a href="{{ route('admin.villas.create') }}" class="add-btn">+ Add Villa</a>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if($villas->isEmpty())
            <div class="empty-state">
                <h3>No villas yet</h3>
                <p>Create your first villa to get started</p>
                <a href="{{ route('admin.villas.create') }}" class="add-btn">Create Villa</a>
            </div>
        @else
            <div class="villa-cards">
                @foreach($villas as $villa)
                    <div class="villa-card">
                        <div class="villa-card-header">
                            <h3>{{ $villa->name }}</h3>
                            <span class="villa-status {{ strtolower($villa->status) }}">
                                {{ ucfirst($villa->status) }}
                            </span>
                        </div>
                        <div class="villa-info">
                            <div class="villa-info-row">
                                <label>Price:</label>
                                <value>Rp {{ number_format($villa->base_price, 0, ',', '.') }}</value>
                            </div>
                            <div class="villa-info-row">
                                <label>Capacity:</label>
                                <value>{{ $villa->capacity }} guests</value>
                            </div>
                            <div class="villa-info-row">
                                <label>Bedrooms:</label>
                                <value>{{ $villa->rooms_total }}</value>
                            </div>
                            <div class="villa-info-row">
                                <label>Description:</label>
                            </div>
                            <div style="font-size: 12px; color: #666; margin-bottom: 8px;">
                                {{ Str::limit($villa->description, 60) }}
                            </div>
                            @if($villa->closed_dates && count($villa->closed_dates) > 0)
                                <div
                                    style="background: #fff3cd; padding: 8px; border-radius: 4px; font-size: 12px; margin-top: 8px;">
                                    <strong>Closed dates:</strong> {{ implode(', ', $villa->closed_dates) }}
                                </div>
                            @endif
                        </div>
                        <div class="villa-card-footer">
                            <a href="{{ route('admin.villas.edit', $villa->id) }}" class="btn-small btn-edit">Edit</a>
                            <form action="{{ route('admin.villas.destroy', $villa->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-delete"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>