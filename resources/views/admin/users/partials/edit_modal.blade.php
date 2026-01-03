<div id="editModal" class="modal">
    <div class="panel">
        <h3 style="margin-top:0">Edit User</h3>
        <form>
            <div style="margin-bottom:8px"><label>Name</label><br><input type="text" style="width:100%;padding:8px"
                    value="John doe"></div>
            <div style="margin-bottom:8px"><label>Email</label><br><input type="email" style="width:100%;padding:8px"
                    value="john@example.com"></div>
            <div style="margin-bottom:8px"><label>Password</label><br><input type="password"
                    style="width:100%;padding:8px" placeholder="New password (leave blank to keep)"></div>
            <div style="margin-bottom:8px"><label>Role</label><br><input type="text" style="width:100%;padding:8px"
                    value="guest"></div>
            <div style="text-align:right"><button type="button"
                    onclick="document.getElementById('editModal').style.display='none'">Cancel</button>
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>