<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Events\OrderStatusChanged;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Booking::with(['user', 'villa'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function realtimeDashboard()
    {
        $recentBookings = Booking::with(['user', 'villa'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.orders.realtime-dashboard', compact('recentBookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'villa']);
        return view('admin.reservations.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $booking->update($validated);

        // Broadcast status change event
        broadcast(new OrderStatusChanged(
            $booking->id,
            $booking->guest_name ?? $booking->user->name,
            $booking->villa->name,
            $oldStatus,
            $validated['status']
        ))->toOthers();

        return redirect()->route('admin.reservations.show', $booking)
            ->with('success', 'Reservation status updated!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation deleted!');
    }

    /**
     * Get latest reservations for real-time updates (AJAX)
     */
    public function getLatestReservations()
    {
        $reservations = Booking::with(['user', 'villa'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'guest_name' => $booking->guest_name ?? $booking->user->name ?? 'Guest',
                    'villa_name' => $booking->villa->name,
                    'check_in_date' => $booking->check_in_date->format('d M Y'),
                    'check_out_date' => $booking->check_out_date->format('d M Y'),
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json(['reservations' => $reservations]);
    }

    /**
     * Get latest orders for real-time feed (AJAX)
     */
    public function getLatestOrders()
    {
        $orders = Booking::with(['villa'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'guest_name' => $booking->guest_name ?? $booking->user->name ?? 'Guest',
                    'villa_name' => $booking->villa->name,
                    'check_in' => $booking->check_in_date->format('d M'),
                    'check_out' => $booking->check_out_date->format('d M'),
                    'total_price' => number_format($booking->total_price, 0, ',', '.'),
                    'status' => $booking->status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json(['orders' => $orders]);
    }
}

