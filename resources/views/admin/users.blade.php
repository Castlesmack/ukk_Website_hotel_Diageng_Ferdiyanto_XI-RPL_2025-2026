@extends('layouts.app')

@section('title','Admin - Users')

@push('styles')
<style>
    .container{max-width:1200px;margin:18px auto;display:flex;gap:18px}
    .sidebar{width:220px;border-right:2px solid #eee;padding:18px}
    .sidebar a{display:block;padding:12px;background:#f5f5f5;border-radius:6px;margin-bottom:8px;color:#333;text-decoration:none}
    .main{flex:1;padding:18px}
    .card{border:2px solid #ddd;border-radius:8px;padding:12px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:8px;border-bottom:1px solid #eee;text-align:left}
    .actions button{margin-right:8px;padding:6px 10px;border-radius:6px}
    .btn-add{display:inline-block;padding:8px 14px;background:#111;color:#fff;border-radius:6px;text-decoration:none}
    .modal{position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.4);display:none;align-items:center;justify-content:center}
    .modal .panel{background:#fff;padding:18px;border-radius:8px;min-width:320px}
</style>
@endpush

@section('content')
    <div class="container">
        <aside class="sidebar">
            <a href="/admin/dashboard">Dashboard</a>
            <a href="/admin/villas/create">Villas</a>
            <a href="/admin/reservations">Reservation</a>
            <a href="/admin/users" style="background:#e6e6e6">Users</a>
            <a href="/admin/finances">Finance</a>
        </aside>

        <main class="main">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <h3>Users</h3>
                <a href="#" class="btn-add" onclick="document.getElementById('createModal').style.display='flex'">Add user</a>
            </div>

            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>No Telp</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $i => $u)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ sprintf('USR%03d', $u->id) }}</td>
                            <td>{{ $u->phone }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->role ?? 'guest' }}</td>
                            <td class="actions">
                                <a href="/admin/users/{{ $u->id }}/edit"><button>Edit</button></a>
                                <form action="/admin/users/{{ $u->id }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    @include('admin.users.partials.create_modal')
    @include('admin.users.partials.edit_modal')

    @push('scripts')
    <script>
        function openEdit(){ document.getElementById('editModal').style.display='flex' }
        // close by clicking background
        document.addEventListener('click', function(e){ if(e.target.classList.contains('modal')) e.target.style.display='none' })
    </script>
    @endpush
@endsection