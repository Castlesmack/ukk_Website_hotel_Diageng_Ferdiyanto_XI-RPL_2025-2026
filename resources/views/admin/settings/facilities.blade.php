@extends('layouts.app')

@section('title', 'Manage Facilities')

@section('content')
    <style>
        .facility-form {
            background: white;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .form-group {
            display: grid;
            gap: 8px;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #5a4a42;
            box-shadow: 0 0 0 3px rgba(90, 74, 66, 0.1);
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #5a4a42 0%, #3d2f2a 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(90, 74, 66, 0.3);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            font-size: 12px;
            padding: 6px 12px;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-toggle {
            background: #ddd;
            color: #333;
            font-size: 12px;
            padding: 6px 12px;
        }

        .btn-toggle.active {
            background: #d4edda;
            color: #155724;
        }

        .facility-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }

        .facility-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            border-color: #5a4a42;
        }

        .facility-info {
            flex: 1;
        }

        .facility-name {
            font-weight: 600;
            color: #2c2c2c;
            margin-bottom: 5px;
        }

        .facility-category {
            font-size: 12px;
            color: #666;
            text-transform: capitalize;
        }

        .facility-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-visible {
            background: #d4edda;
            color: #155724;
        }

        .status-hidden {
            background: #f8d7da;
            color: #721c24;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-left: 4px solid #5a4a42;
            border-radius: 6px;
            margin-bottom: 15px;
            margin-top: 25px;
        }

        .section-header h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #2c2c2c;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .sidebar {
            background: #f8f9fa;
            padding: 20px;
            min-height: 100vh;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar a, .sidebar button {
            padding: 12px;
            background: white;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-align: left;
            font-weight: 500;
            transition: all 0.3s;
        }

        .sidebar a:hover, .sidebar button:hover {
            background: #e8e8e8;
            transform: translateX(4px);
        }

        .sidebar a.active {
            background: #5a4a42;
            color: white;
        }
    </style>

    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav>
                <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <button
                        onclick="document.getElementById('manage-menu').style.display = document.getElementById('manage-menu').style.display === 'none' ? 'flex' : 'none'"
                        style="text-align: left;">🏡 Manage</button>
                    <div id="manage-menu" style="display: flex; flex-direction: column; gap: 5px; margin-left: 10px;">
                        <a href="{{ route('admin.villas.index') }}" style="font-size: 13px; padding: 8px;">Villa</a>
                        <a href="{{ route('admin.settings.homepage') }}" style="font-size: 13px; padding: 8px;">Homepage</a>
                    </div>
                </div>
                <a href="{{ route('admin.reservations.index') }}">📅 Reservation</a>
                <a href="{{ route('admin.users.index') }}">👥 Users</a>
                <a href="{{ route('admin.finances.index') }}">💰 Finance</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px;">
            <h1 style="margin: 0 0 30px 0; font-size: 28px; color: #2c2c2c;">Manage Facilities</h1>

            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            <!-- Add Facility Form -->
            <div class="facility-form">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #2c2c2c;">➕ Add New Facility</h3>
                <form action="{{ route('admin.settings.facilities.store') }}" method="POST" style="display: grid; gap: 15px;">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category" id="category" required>
                                <option value="">Select Category</option>
                                <option value="public_facilities">Public Facilities</option>
                                <option value="connectivity">Connectivity</option>
                                <option value="other_activities">Other Activities</option>
                                <option value="transportation">Transportation</option>
                                <option value="room_amenities">Room Amenities</option>
                                <option value="entertainment">Entertainment</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Facility Name</label>
                            <input type="text" name="name" id="name" required placeholder="e.g., WiFi, Swimming Pool, Gym..."
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="icon">Icon (Emoji)</label>
                            <input type="text" name="icon" id="icon" placeholder="e.g., 📶 📺 🏊 💪"
                                autocomplete="off" maxlength="10" value="✨" title="Paste an emoji or emoji combination">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Add Facility</button>
                </form>
            </div>

            <!-- Facilities List -->
            <div style="background: white; padding: 25px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #2c2c2c;">Facilities List</h3>

                @php
                    $grouped = $facilities->groupBy('category');
                @endphp

                @if ($facilities->count() > 0)
                    @foreach ($grouped as $category => $items)
                        <div class="section-header">
                            <h4>{{ ucfirst(str_replace('_', ' ', $category)) }} ({{ $items->count() }})</h4>
                        </div>
                        <div style="display: grid; gap: 0; margin-bottom: 0;">
                            @foreach ($items as $facility)
                                <div class="facility-card">
                                    <div class="facility-info">
                                        <div class="facility-name">
                                            <span style="font-size: 20px; margin-right: 10px;">{{ $facility->icon ?? '✨' }}</span>
                                            {{ $facility->name }}
                                        </div>
                                        <div class="facility-category">{{ ucfirst(str_replace('_', ' ', $facility->category)) }}</div>
                                    </div>
                                    <div class="facility-actions">
                                        <span class="status-badge {{ $facility->is_visible ? 'status-visible' : 'status-hidden' }}">
                                            {{ $facility->is_visible ? '✓ Visible' : '✕ Hidden' }}
                                        </span>
                                        <form action="{{ route('admin.settings.facilities.destroy', $facility->id) }}" method="POST"
                                            style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Delete this facility?')">🗑️ Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <p style="color: #999; text-align: center; padding: 30px;">No facilities yet. Add a new facility above.</p>
                @endif
            </div>
        </div>
    </div>
@endsection