<!doctype html>
<html>

@extends('layouts.app')

@section('title','Villa Detail')

@section('content')
    <a href="#" style="display:inline-block;margin:12px 0">&larr; Back</a>
    <h3>Villa Tipe - 0608</h3>
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px">
        <div style="border:1px solid #eee;padding:12px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
                <div style="height:160px;background:#000"></div>
                <div style="display:grid;grid-template-rows:repeat(3,1fr);gap:6px">
                    <div style="background:#000"></div>
                    <div style="background:#000"></div>
                    <div style="background:#ccc;text-align:center;align-items:center;display:flex;justify-content:center">See all Photos</div>
                </div>
            </div>
            <div style="margin-top:12px">Fasilitas<br>- WiFi<br>- AC</div>
        </div>
        <aside style="border:1px solid #eee;padding:12px">
            <div style="font-weight:bold">Rp 800.000,00 / malam</div>
            <div style="margin-top:8px">Check-in <br>Check-out</div>
            <div style="margin-top:8px">
                <label>Jumlah Tamu</label>
                <input type="number" value="2" style="width:100%;padding:8px">
            </div>
            <div style="margin-top:8px"><button style="width:100%;padding:10px;background:#000;color:#fff;border:none">Order</button></div>
        </aside>
    </div>
@endsection

            <aside style="border:1px solid #eee;padding:12px">
                <div style="font-weight:bold">Rp 800.000,00 / malam</div>
                <div style="margin-top:8px">Check-in <br>Check-out</div>
                <div style="margin-top:8px">
                    <label>Jumlah Tamu</label>
                    <input type="number" value="2" style="width:100%;padding:8px">
                </div>
                <div style="margin-top:8px"><button
                        style="width:100%;padding:10px;background:#000;color:#fff;border:none">Order</button></div>
            </aside>
        </div>
    </div>
</body>

</html>