<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Home - Before Login</title>
    @extends('layouts.app')

    @section('title','Home - Before Login')

    @section('content')
        <div style="height:160px;border:1px solid #000;border-radius:6px;margin-bottom:16px"></div>
        <section style="border:1px solid #e6e6e6;padding:12px;margin-bottom:12px">
            <p>Penyewaan villa tipe ... (intro text)</p>
        </section>
        <section>
            <h4>Villa</h4>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
                @for($i=0;$i<8;$i++)
                    <div style="border:1px solid #000;padding:10px;text-align:center">Villa Ade - 0608<br><small>Kapasitas 8 orang</small></div>
                @endfor
            </div>
        </section>
    @endsection