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
}

