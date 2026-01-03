<div style="position:relative;display:inline-block">
    <button
        style="width:44px;height:44px;border-radius:50%;background:#1e90ff;border:none;color:#fff">&#128100;</button>
    <div
        style="position:absolute;right:0;top:48px;min-width:160px;border:1px solid #ddd;background:#fff;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,0.08)">
        <a href="/user/profile"
            style="display:block;padding:10px;border-bottom:1px solid #eee;color:#222;text-decoration:none">Profile</a>
        <a href="/user/bookings"
            style="display:block;padding:10px;border-bottom:1px solid #eee;color:#222;text-decoration:none">My
            Bookings</a>
        <form action="/logout" method="POST" style="margin:0">
            @csrf
            <button type="submit"
                style="display:block;width:100%;padding:10px;border:none;background:none;color:#c62828;text-decoration:none;text-align:left;cursor:pointer">Log
                out</button>
        </form>
    </div>
</div>