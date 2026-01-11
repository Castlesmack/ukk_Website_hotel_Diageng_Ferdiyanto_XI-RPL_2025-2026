<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVillas = Villa::count();
        $totalGuests = Booking::distinct('user_id')->count('user_id');
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $totalBookings = Booking::count();

        $recentBookings = Booking::with(['user', 'villa'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $bookingsByMonth = Booking::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(12)
            ->get();

        return view('admin.dashboard', compact(
            'totalVillas',
            'totalGuests',
            'totalRevenue',
            'totalBookings',
            'recentBookings',
            'bookingsByMonth'
        ));
    }
}
