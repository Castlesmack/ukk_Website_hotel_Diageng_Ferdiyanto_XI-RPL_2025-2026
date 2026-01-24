@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        .profile-layout {
            display: flex;
            gap: 30px;
            margin: 40px auto;
            max-width: 1300px;
            padding: 30px 20px;
            background: white;
            border-radius: 12px;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
        }

        .sidebar-card {
            background: #FAF2E8;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .sidebar-header {
            background: linear-gradient(135deg, #f5f6f8 0%, #f8f9fa 100%);
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .sidebar-header h3 {
            margin: 0;
            color: #333;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 8px;
            background: white;
        }

        .sidebar .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            margin-bottom: 4px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #495057;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .sidebar .menu-item:hover {
            background: #f5f6f8;
            color: #333;
            border-color: #e9ecef;
        }

        .sidebar .menu-item.active {
            background: #f05b4f;
            color: white;
            border-color: #f05b4f;
        }

        .sidebar .menu-item.active:hover {
            background: #d84539;
        }

        .menu-icon {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            min-width: 0;
        }

        .content-header {
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
        }

        .content-header h2 {
            margin: 0;
            color: #1a1a1a;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .content-header p {
            margin: 8px 0 0 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* Form Container */
        .form-container {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1a1a1a;
            font-size: 14px;
        }

        .required-indicator {
            color: #dc3545;
            font-weight: 700;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #fafbfc;
        }

        .form-group input:hover {
            border-color: #d0d8e0;
        }

        .form-group input:focus {
            outline: none;
            border-color: #f05b4f;
            background: #FAF2E8;
            box-shadow: 0 0 0 3px rgba(240, 91, 79, 0.1);
        }

        .form-divider {
            grid-column: 1 / -1;
            height: 1px;
            background: #FAF2E8;
            margin: 10px 0;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            gap: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-save {
            background: #f05b4f;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .btn-save:hover {
            background: #d84539;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(240, 91, 79, 0.3);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        .form-hint {
            margin-top: 8px;
            font-size: 12px;
            color: #6c757d;
        }

        /* Alert Messages */
        .alert {
            grid-column: 1 / -1;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 0;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-layout {
                flex-direction: column;
                gap: 20px;
            }

            .sidebar {
                width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .content-header h2 {
                font-size: 24px;
            }

            .form-container {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .profile-layout {
                margin: 20px auto;
                padding: 0 12px;
            }

            .form-container {
                padding: 16px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-save {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="profile-layout">
        <aside class="sidebar">
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <h3>Menu</h3>
                </div>
                <div class="sidebar-menu">
                    <a href="{{ route('user.profile') }}" class="menu-item active">
                        <span class="menu-icon"></span>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('user.bookings') }}" class="menu-item">
                        <span class="menu-icon"></span>
                        <span>My Bookings</span>
                    </a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h2>My Profile</h2>
                <p>Manage your personal information and account settings</p>
            </div>

            <div class="form-container">
                <form method="POST" action="/user/profile">
                    @csrf

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">
                                Name
                                <span class="required-indicator">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required
                                placeholder="Enter your full name">
                        </div>

                        <div class="form-group">
                            <label for="email">
                                ✉️ Email
                                <span class="required-indicator">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required
                                placeholder="Enter your email address">
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                📞 Phone
                                <span class="required-indicator">*</span>
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                                placeholder="Enter your phone number">
                            <div class="form-hint">Format: +62 XXX-XXXX-XXXX</div>
                        </div>

                        <div class="form-divider"></div>

                        <div class="form-group">
                            <label for="password">
                                🔐 New Password
                            </label>
                            <input type="password" id="password" name="password"
                                placeholder="Leave blank to keep current password">
                            <div class="form-hint">Password must be at least 8 characters</div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">💾 Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection