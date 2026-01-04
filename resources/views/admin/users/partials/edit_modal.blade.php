<div id="editModal" class="modal">
        <div class="panel">
                <h3 style="margin-top:0">Edit User</h3>
                <form method="POST" action="{{ route('admin.users.update', $user ?? null) }}">
                        @csrf
                        @method('PUT')
                        <div style="margin-bottom:8px"><label>Name</label><br><input type="text" name="name"
                                        style="width:100%;padding:8px" value="{{ $user->name ?? '' }}" required></div>
                        <div style="margin-bottom:8px"><label>Email</label><br><input type="email" name="email"
                                        style="width:100%;padding:8px" value="{{ $user->email ?? '' }}" required></div>
                        <div style="margin-bottom:8px"><label>Password</label><br><input type="password" name="password"
                                        style="width:100%;padding:8px" placeholder="New password (leave blank to keep)">
                        </div>
                        <div style="margin-bottom:8px"><label>Phone</label><br><input type="text" name="phone"
                                        style="width:100%;padding:8px" value="{{ $user->phone ?? '' }}"></div>
                        <div style="margin-bottom:8px"><label>Role</label><br><select name="role"
                                        style="width:100%;padding:8px">
                                        <option value="guest" {{ ($user->role ?? 'guest') == 'guest' ? 'selected' : '' }}>
                                                Guest</option>
                                        <option value="receptionist" {{ ($user->role ?? '') == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                                        <option value="admin" {{ ($user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                </select></div>
                        <div style="text-align:right"><button type="button"
                                        onclick="document.getElementById('editModal').style.display='none'">Cancel</button>
                                <button type="submit">Save</button>
                        </div>
                </form>
        </div>
</div>