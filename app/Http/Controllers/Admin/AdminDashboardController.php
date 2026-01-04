<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total villas
        $totalVillas = Villa::count();

        // Total revenue from completed/confirmed bookings
        $totalRevenue = Booking::whereIn('status', ['completed', 'confirmed'])
            ->sum('total_price');

        // Total guests
        $totalGuests = Booking::whereIn('status', ['confirmed', 'checked_in', 'completed'])
            ->sum('guest_count');

        // Total bookings
        $totalBookings = Booking::count();

        // Total users
        $totalUsers = User::count();

        // Revenue trend (last 7 days)
        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $revenue = Booking::whereDate('created_at', $date)
                ->whereIn('status', ['completed', 'confirmed'])
                ->sum('total_price');
            $revenueTrend[] = [
                'day' => Carbon::parse($date)->format('D'),
                'revenue' => $revenue,
                'height' => max(20, ($revenue / 1000000) * 150) // Scale for chart
            ];
        }

        // Top 5 villas by bookings
        $topVillas = Villa::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        // Recent bookings
        $recentBookings = Booking::orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'totalVillas' => $totalVillas,
            'totalRevenue' => $totalRevenue,
            'totalGuests' => $totalGuests,
            'totalBookings' => $totalBookings,
            'totalUsers' => $totalUsers,
            'revenueTrend' => $revenueTrend,
            'topVillas' => $topVillas,
            'recentBookings' => $recentBookings,
        ]);
    }
}
