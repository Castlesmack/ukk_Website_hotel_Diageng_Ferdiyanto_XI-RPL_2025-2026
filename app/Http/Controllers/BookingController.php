<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Villa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Get availability calendar data for a villa
     * 
     * Public API endpoint - tidak perlu authentication
     * Format response: JSON dengan booked dates yang sudah di-parse
     * 
     * @param int $id Villa ID
     * @return \Illuminate\Http\JsonResponse
     * 
     * Example: GET /api/villa/1/availability
     * Response: {
     *   "villa_id": 1,
     *   "booked_dates": [
     *     { "check_in_date": "2026-01-28", "check_out_date": "2026-01-31" },
     *     { "check_in_date": "2026-02-05", "check_out_date": "2026-02-08" }
     *   ],
     *   "timestamp": "2026-01-28T10:30:00Z"
     * }
     */
    public function getAvailability($id)
    {
        // Verify villa exists
        $villa = Villa::find($id);
        if (!$villa) {
            return response()->json([
                'error' => 'Villa tidak ditemukan',
                'villa_id' => $id
            ], 404);
        }

        // Get all booked dates (confirmed & pending) for this villa
        $bookedDates = Booking::where('villa_id', $id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('check_out_date', '>=', now()->format('Y-m-d'))
            ->select('check_in_date', 'check_out_date')
            ->orderBy('check_in_date')
            ->get();
        
        return response()->json([
            'success' => true,
            'villa_id' => $id,
            'villa_name' => $villa->name,
            'booked_dates' => $bookedDates,
            'total_booked_ranges' => $bookedDates->count(),
            'timestamp' => now(),
            'message' => 'Data tersedia'
        ]);
    }

    /**
     * Validate if dates are available for a specific villa
     * 
     * POST endpoint untuk validasi ketersediaan tanggal
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Example: POST /api/villa/availability/validate
     * Body: {
     *   "villa_id": 1,
     *   "check_in": "2026-01-29",
     *   "check_out": "2026-02-01"
     * }
     * Response: {
     *   "available": true,
     *   "message": "Tanggal tersedia",
     *   "nights": 3,
     *   "total_price": 450000
     * }
     */
    public function validateAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'villa_id' => 'required|exists:villas,id',
            'check_in' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'check_out' => 'required|date|date_format:Y-m-d|after:check_in',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'available' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $villa = Villa::find($validated['villa_id']);

        // Check for conflicting bookings
        $conflict = Booking::where('villa_id', $validated['villa_id'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->where(function($q) use ($validated) {
                $q->where('check_in_date', '<', $validated['check_out'])
                  ->where('check_out_date', '>', $validated['check_in']);
            })
            ->first();

        // Calculate nights and total price
        $checkInDate = Carbon::parse($validated['check_in']);
        $checkOutDate = Carbon::parse($validated['check_out']);
        $nights = $checkOutDate->diffInDays($checkInDate);
        $totalPrice = $villa->base_price * $nights;

        return response()->json([
            'success' => true,
            'available' => !$conflict,
            'villa_id' => $validated['villa_id'],
            'villa_name' => $villa->name,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'nights' => $nights,
            'base_price' => $villa->base_price,
            'total_price' => $totalPrice,
            'message' => $conflict 
                ? 'Tanggal tidak tersedia - ada booking lain' 
                : 'Tanggal tersedia untuk booking',
            'conflicting_booking' => $conflict ? [
                'check_in_date' => $conflict->check_in_date,
                'check_out_date' => $conflict->check_out_date,
                'status' => $conflict->status,
            ] : null
        ]);
    }

    /**
     * Get availability for multiple villas at once
     * 
     * Untuk search/filter multiple villas
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Example: POST /api/villas/availability
     * Body: {
     *   "villa_ids": [1, 2, 3],
     *   "check_in": "2026-01-29",
     *   "check_out": "2026-02-01"
     * }
     */
    public function checkMultipleVillasAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'villa_ids' => 'required|array',
            'villa_ids.*' => 'exists:villas,id',
            'check_in' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'check_out' => 'required|date|date_format:Y-m-d|after:check_in',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $villaIds = $validated['villa_ids'];

        // Get all villas with their availability status
        $villas = Villa::whereIn('id', $villaIds)->get();
        
        $availability = [];
        foreach ($villas as $villa) {
            $conflict = Booking::where('villa_id', $villa->id)
                ->whereIn('status', ['confirmed', 'pending'])
                ->where(function($q) use ($validated) {
                    $q->where('check_in_date', '<', $validated['check_out'])
                      ->where('check_out_date', '>', $validated['check_in']);
                })
                ->exists();

            $availability[] = [
                'villa_id' => $villa->id,
                'villa_name' => $villa->name,
                'available' => !$conflict,
                'base_price' => $villa->base_price,
            ];
        }

        return response()->json([
            'success' => true,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'total_villas_checked' => count($villas),
            'villas_available' => collect($availability)->where('available', true)->count(),
            'availability' => $availability
        ]);
    }

    /**
     * Get booking statistics for a villa
     * 
     * @param int $id Villa ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBookingStats($id)
    {
        $villa = Villa::find($id);
        if (!$villa) {
            return response()->json(['error' => 'Villa tidak ditemukan'], 404);
        }

        $totalBookings = Booking::where('villa_id', $id)->count();
        $confirmedBookings = Booking::where('villa_id', $id)
            ->where('status', 'confirmed')
            ->count();
        $pendingBookings = Booking::where('villa_id', $id)
            ->where('status', 'pending')
            ->count();
        $totalRevenue = Booking::where('villa_id', $id)
            ->where('status', 'confirmed')
            ->sum('total_price');

        return response()->json([
            'success' => true,
            'villa_id' => $id,
            'villa_name' => $villa->name,
            'statistics' => [
                'total_bookings' => $totalBookings,
                'confirmed_bookings' => $confirmedBookings,
                'pending_bookings' => $pendingBookings,
                'total_revenue' => $totalRevenue,
                'occupancy_rate' => $this->calculateOccupancyRate($id),
            ]
        ]);
    }

    /**
     * Calculate occupancy rate for a villa
     * 
     * @param int $villaId
     * @return float Occupancy rate percentage
     */
    private function calculateOccupancyRate($villaId)
    {
        $currentMonth = Carbon::now();
        $daysInMonth = $currentMonth->daysInMonth;

        $bookedDays = 0;
        $bookings = Booking::where('villa_id', $villaId)
            ->where('status', 'confirmed')
            ->whereMonth('check_in_date', $currentMonth->month)
            ->whereYear('check_in_date', $currentMonth->year)
            ->get();

        foreach ($bookings as $booking) {
            $nights = $booking->check_out_date->diffInDays($booking->check_in_date);
            $bookedDays += $nights;
        }

        return round(($bookedDays / $daysInMonth) * 100, 2);
    }
}
