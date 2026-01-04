<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Villa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    public function dashboard()
    {
        // Total guests
        $totalGuests = Booking::whereIn('status', ['confirmed', 'checked_in'])
            ->sum('guest_count');

        // Total bookings
        $totalBookings = Booking::whereIn('status', ['confirmed', 'checked_in', 'completed'])
            ->count();

        // Total revenue
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'checked_in', 'completed'])
            ->sum('total_price');

        // Schedule - upcoming check-ins and check-outs
        $today = Carbon::now()->toDateString();
        $upcomingSchedule = Booking::where('check_in_date', '>=', $today)
            ->orWhere('check_out_date', '>=', $today)
            ->orderBy('check_in_date')
            ->limit(6)
            ->get();

        // Weekly occupancy data (last 5 days)
        $weeklyData = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $bookings = Booking::whereDate('check_in_date', '<=', $date)
                ->whereDate('check_out_date', '>', $date)
                ->count();
            $weeklyData[] = [
                'day' => Carbon::parse($date)->format('l'),
                'count' => $bookings,
                'height' => max(52, $bookings * 30)
            ];
        }

        return view('reception.dashboard', [
            'totalGuests' => $totalGuests,
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'upcomingSchedule' => $upcomingSchedule,
            'weeklyData' => $weeklyData,
        ]);
    }

    public function reservations(Request $request)
    {
        $query = Booking::query();

        // Search by itinerary ID/booking code
        if ($request->filled('itinerary')) {
            $query->where('booking_code', 'like', '%' . $request->itinerary . '%');
        }

        // Filter by date type
        $dateType = $request->get('date_type', 'check_in');

        // Date range filter
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->toDateString();
            if ($dateType === 'check_out') {
                $query->whereDate('check_out_date', '>=', $startDate);
            } else {
                $query->whereDate('check_in_date', '>=', $startDate);
            }
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->toDateString();
            if ($dateType === 'check_out') {
                $query->whereDate('check_out_date', '<=', $endDate);
            } else {
                $query->whereDate('check_in_date', '<=', $endDate);
            }
        }

        $bookings = $query->orderByDesc('created_at')->paginate(15);

        return view('reception.reservation', [
            'bookings' => $bookings,
        ]);
    }

    public function calendar(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $villaId = $request->get('villa_id', null);

        $currentDate = Carbon::create($year, $month, 1);
        $daysInMonth = $currentDate->daysInMonth;
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $dayOfWeek = $startOfMonth->dayOfWeek;

        // Get all villas for dropdown
        $villas = Villa::all();

        // Get bookings for the month
        $bookings = Booking::whereYear('check_in_date', '<=', $year)
            ->whereYear('check_out_date', '>=', $year)
            ->whereMonth('check_in_date', '<=', $month)
            ->whereMonth('check_out_date', '>=', $month)
            ->when($villaId, function ($query) use ($villaId) {
                return $query->where('villa_id', $villaId);
            })
            ->get();

        // Prepare calendar data
        $calendarDays = [];
        
        // Add empty days for days before month starts
        for ($i = 0; $i < $dayOfWeek; $i++) {
            $calendarDays[] = [
                'day' => null,
                'isCurrentMonth' => false,
                'bookings' => [],
                'totalPrice' => 0
            ];
        }

        // Add days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day)->toDateString();
            $dayBookings = $bookings->filter(function ($booking) use ($date) {
                return $booking->check_in_date->toDateString() <= $date &&
                       $booking->check_out_date->toDateString() > $date;
            });

            $calendarDays[] = [
                'day' => $day,
                'date' => $date,
                'isCurrentMonth' => true,
                'bookings' => $dayBookings,
                'totalPrice' => $dayBookings->sum('total_price'),
                'isBooked' => $dayBookings->count() > 0
            ];
        }

        return view('reception.calendar', [
            'calendarDays' => $calendarDays,
            'villas' => $villas,
            'currentMonth' => $currentDate->format('F Y'),
            'month' => $month,
            'year' => $year,
            'villaId' => $villaId,
        ]);
    }
}
