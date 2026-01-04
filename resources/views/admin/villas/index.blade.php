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
            width: 180px;
            background: white;
            padding: 0;
            border-radius: 0;
        }

        .sidebar h3 {
            margin-top: 0;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            padding: 0 15px;
            padding-top: 15px;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 15px;
        }

        .sidebar-nav a {
            display: block;
            padding: 12px 15px;
            background: #e9ecef;
            color: #212529;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: #007bff;
            color: white;
            border-left-color: #0056b3;
            transform: translateX(4px);
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
            border-bottom: 1px solid #dee2e6;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .table th {
            background: #f8f9fa;
        }

        .btn-sm {
            padding: 4px 8px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="admin-layout">
        <div class="sidebar">
            <h3>Ade Villa Admin</h3>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="menu-item">Dashboard</a>
                <a href="{{ route('admin.manage') }}" class="menu-item active">Manage</a>
                <a href="{{ route('admin.users.index') }}" class="menu-item">Manage Users</a>
                <a href="{{ route('admin.reservations') }}" class="menu-item">Reservations</a>
                <a href="{{ route('admin.finances') }}" class="menu-item">Finances</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Manage Villas</h2>
                <a href="{{ route('admin.villas.create') }}" class="btn-primary">Add New Villa</a>
            </div>

            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($villas as $villa)
                        <tr>
                            <td>{{ $villa->id }}</td>
                            <td>{{ $villa->name }}</td>
                            <td>{{ $villa->capacity }}</td>
                            <td>Rp {{ number_format($villa->base_price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($villa->status) }}</td>
                            <td>
                                <a href="{{ route('admin.villas.edit', $villa->id) }}" class="btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('admin.villas.destroy', $villa->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection