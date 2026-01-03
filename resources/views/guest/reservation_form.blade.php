<!doctype html>
<html>

@extends('layouts.app')

@section('title','Reservation Form')

@section('content')
    <a href="#">&larr; Back</a>
    <h3>Villa Tipe - 0608</h3>
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px">
        <div style="border:1px solid #eee;padding:12px">
            <form>
                <div><label>Check In:</label><input type="date" style="width:100%"></div>
                <div><label>Check Out:</label><input type="date" style="width:100%"></div>
                <div><label>Tamu:</label><input type="text" style="width:100%"></div>
                <div><label>Nama Tamu:</label><input type="text" style="width:100%"></div>
                <div><label>Email:</label><input type="email" style="width:100%"></div>
                <div><label>No Telp:</label><input type="text" style="width:100%"></div>
                <div><label>Permintaan khusus:</label><textarea style="width:100%"></textarea></div>
                <div style="text-align:right"><button style="background:#000;color:#fff;padding:8px 12px;border:none">Lanjutkan</button></div>
            </form>
        </div>
        <aside style="border:1px solid #eee;padding:12px">
            <h4>Rincian Pesanan</h4>
            <div>Check in: --</div>
            <div>Check out: --</div>
            <div>Harga: Rp 800.000</div>
            <div style="margin-top:12px"><button style="width:100%;padding:10px;background:#000;color:#fff;border:none">Proceed to Payment</button></div>
        </aside>
    </div>
@endsection

            </div>
            <aside style="border:1px solid #eee;padding:12px">
                <h4>Rincian Pesanan</h4>
                <div>Check in: --</div>
                <div>Check out: --</div>
                <div>Harga: Rp 800.000</div>
                <div style="margin-top:12px"><button
                        style="width:100%;padding:10px;background:#000;color:#fff;border:none">Proceed to
                        Payment</button></div>
            </aside>
        </div>
    </div>
</body>

</html>