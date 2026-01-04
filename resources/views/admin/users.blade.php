@extends('layouts.app')

@section('title', 'Users - Admin')

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
            color: #007bff;
        }

        .add-btn {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
        }

        .add-btn:hover {
            background: #218838;
        }

        .users-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .role-admin {
            background: #dc3545;
            color: white;
        }

        .role-receptionist {
            background: #ffc107;
            color: black;
        }

        .role-guest {
            background: #007bff;
            color: white;
        }

        .action-btn {
            padding: 4px 8px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 12px;
            margin-right: 5px;
        }

        .edit-btn {
            background: #ffc107;
            color: black;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
    </style>
@endpush

@section('content')
    <div class="admin-layout">
        <aside class="sidebar">
            <h3>Ade Villa Admin</h3>
            <nav>
                <a href="/admin/dashboard" class="menu-item">Dashboard</a>
                <a href="/admin/manage" class="menu-item">Manage</a>
                <a href="/admin/reservations" class="menu-item">Reservations</a>
                <a href="/admin/users" class="menu-item active">Users</a>
                <a href="/admin/finances" class="menu-item">Finance</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header">
                <h2>Users</h2>
                <a href="{{ route('admin.users.create') }}" class="add-btn">Add New User</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <strong>25</strong>
                </div>
                <div class="stat-card">
                    <h3>Admins</h3>
                    <strong>2</strong>
                </div>
                <div class="stat-card">
                    <h3>Receptionists</h3>
                    <strong>3</strong>
                </div>
                <div class="stat-card">
                    <h3>Guests</h3>
                    <strong>20</strong>
                </div>
            </div>

            <div class="users-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td><span
                                        class="role-badge role-{{ $user->role ?? 'guest' }}">{{ ucfirst($user->role ?? 'guest') }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn edit-btn">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn"
                                            onclick="return confirm('Delete this user?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

@endsection