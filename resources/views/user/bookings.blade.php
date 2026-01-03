<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>My Bookings</title>
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

        .nav {
            width: 180px
        }

        .panel {
            flex: 1;
            border: 1px solid #e6daca;
            padding: 18px;
            border-radius: 6px
        }

        .booking {
            border: 1px solid #e6daca;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 10px
        }
    </style>
</head>

@extends('layouts.app')

@section('title','My Bookings')

@section('content')
    <div style="display:flex;gap:18px">
        <aside style="width:180px">
            <div style="border:1px solid #e6daca;padding:12px;border-radius:6px;margin-bottom:12px">Profil</div>
            <div style="border:1px solid #e6daca;padding:12px;border-radius:6px">My Bookings</div>
        </aside>
        <main style="flex:1;border:1px solid #e6daca;padding:18px;border-radius:6px">
            <div style="border:1px solid #e6daca;padding:12px;border-radius:6px;margin-bottom:10px">
                <div><strong>Order ID: 14524621</strong></div>
                <div>Villa Tipe - 0608</div>
                <div>2 Tamu &nbsp; 20/12/2025 - 21/12/2025</div>
            </div>
        </main>
    </div>
@endsection
