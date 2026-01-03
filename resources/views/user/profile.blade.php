<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff
        }

        .layout {
            max-width: 1000px;
            margin: 18px auto;
            display: flex;
            gap: 18px
        }

        @extends('layouts.app')

        @section('title','Profil Saya')

        @section('content')
            <div style="display:flex;gap:18px">
                <aside style="width:180px">
                    <div style="border:1px solid #e6daca;padding:12px;border-radius:6px;margin-bottom:12px">Profil</div>
                    <div style="border:1px solid #e6daca;padding:12px;border-radius:6px">My Bookings</div>
                </aside>
                <main style="flex:1;border:1px solid #e6daca;padding:18px;border-radius:6px">
                    <h4>Profil</h4>
                    <form method="POST" action="#">
                        @csrf
                        <label>Name*</label>
                        <input type="text" name="name" value="John Doe" style="width:100%;padding:8px;margin-bottom:8px">
                        <label>Email*</label>
                        <input type="email" name="email" value="john@example.com" style="width:100%;padding:8px;margin-bottom:8px">
                        <label>No Telp*</label>
                        <input type="text" name="phone" value="081234567890" style="width:100%;padding:8px;margin-bottom:8px">
                        <label>Password*</label>
                        <input type="password" name="password" placeholder="New password" style="width:100%;padding:8px;margin-bottom:8px">
                        <div style="text-align:right"><button class="btn" style="background:#000;color:#fff;padding:8px 14px;border:none;border-radius:4px">Simpan</button></div>
                    </form>
                </main>
            </div>
        @endsection

                <input type="text" name="name" value="John Doe">
                <label>Email*</label>
                <input type="email" name="email" value="john@example.com">
                <label>No Telp*</label>
                <input type="text" name="phone" value="081234567890">
                <label>Password*</label>
                <input type="password" name="password" placeholder="New password">
                <div style="text-align:right"><button class="btn">Simpan</button></div>
            </form>
        </main>
    </div>
</body>

</html>