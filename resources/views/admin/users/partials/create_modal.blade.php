<div id="createModal" class="modal">
        <div class="panel">
                <h3 style="margin-top:0">Add user</h3>
                <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        <div style="margin-bottom:8px"><label>Name</label><br><input type="text" name="name"
                                        style="width:100%;padding:8px" placeholder="Name" required></div>
                        <div style="margin-bottom:8px"><label>Email</label><br><input type="email" name="email"
                                        style="width:100%;padding:8px" placeholder="Email" required></div>
                        <div style="margin-bottom:8px"><label>Password</label><br><input type="password" name="password"
                                        style="width:100%;padding:8px" placeholder="Password" required></div>
                        <div style="margin-bottom:8px"><label>Phone</label><br><input type="text" name="phone"
                                        style="width:100%;padding:8px" placeholder="Phone"></div>
                        <div style="margin-bottom:8px"><label>Role</label><br><select name="role"
                                        style="width:100%;padding:8px">
                                        <option value="guest">Guest</option>
                                        <option value="receptionist">Receptionist</option>
                                        <option value="admin">Admin</option>
                                </select></div>
                        <div style="text-align:right"><button type="button"
                                        onclick="document.getElementById('createModal').style.display='none'">Cancel</button>
                                <button type="submit">Add</button>
                        </div>
                </form>
        </div>
</div>