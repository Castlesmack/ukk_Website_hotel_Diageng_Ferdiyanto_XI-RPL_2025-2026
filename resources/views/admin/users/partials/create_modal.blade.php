<div id="createModal" class="modal">
    <div class="panel">
        <h3 style="margin-top:0">Add user</h3>
        <form>
            <div style="margin-bottom:8px"><label>Name</label><br><input type="text" style="width:100%;padding:8px"
                    placeholder="Name"></div>
            <div style="margin-bottom:8px"><label>Email</label><br><input type="email" style="width:100%;padding:8px"
                    placeholder="Email"></div>
            <div style="margin-bottom:8px"><label>Password</label><br><input type="password"
                    style="width:100%;padding:8px" placeholder="Password"></div>
            <div style="margin-bottom:8px"><label>Role</label><br><input type="text" style="width:100%;padding:8px"
                    placeholder="Role"></div>
            <div style="text-align:right"><button type="button"
                    onclick="document.getElementById('createModal').style.display='none'">Cancel</button>
                <button type="submit">Add</button>
            </div>
        </form>
    </div>
</div>