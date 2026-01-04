<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'villa']);

        // Filter by booking ID or user name
        if ($request->filled('booking')) {
            $search = $request->get('booking');
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%$search%")
                  ->orWhereHas('user', function($subq) use ($search) {
                      $subq->where('name', 'like', "%$search%");
                  });
            });
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $dateType = $request->get('date_type', 'check-in');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            if ($dateType === 'check-in') {
                $query->whereBetween('check_in_date', [$startDate, $endDate]);
            } else {
                $query->whereBetween('check_out_date', [$startDate, $endDate]);
            }
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.reservations', compact('bookings'));
    }
}
