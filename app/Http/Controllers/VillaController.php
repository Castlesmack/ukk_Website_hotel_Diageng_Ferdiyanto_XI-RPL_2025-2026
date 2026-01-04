<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villa;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VillaController extends Controller
{
    public function index()
    {
        $villas = Villa::all();
        return view('guest.home', compact('villas'));
    }

    public function search(Request $request)
    {
        $capacity = $request->get('capacity');
        $checkin = $request->get('checkin') ?? now()->format('Y-m-d');
        $checkout = $request->get('checkout');
        $guests = $request->get('guests', 1);
        $price = $request->get('price');

        $query = Villa::query();

        // Apply OR logic - villa matches if ANY criteria is met
        if ($capacity) {
            $query->where('capacity', '>=', $capacity);
        }
        
        if ($price) {
            $query->orWhere('base_price', '<=', $price);
        }
        
        $villas = $query->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'villas' => $villas->map(function($villa) {
                    return [
                        'id' => $villa->id,
                        'name' => $villa->name,
                        'capacity' => $villa->capacity,
                        'base_price' => $villa->base_price,
                        'rooms_total' => $villa->rooms_total,
                        'description' => $villa->description,
                        'status' => $villa->status,
                    ];
                })
            ]);
        }

        return view('guest.home', compact('villas', 'checkin', 'checkout', 'guests'));
    }

    public function detail($id)
    {
        $villa = Villa::findOrFail($id);
        return view('guest.villa_detail', compact('villa'));
    }

    public function reservationForm(Request $request)
    {
        $villaId = $request->get('villa_id');
        $checkin = $request->get('checkin');
        $checkout = $request->get('checkout');
        $guests = $request->get('guests', 2);

        $villa = null;
        if ($villaId) {
            $villa = Villa::find($villaId);
        }

        return view('guest.reservation_form', compact('villa', 'checkin', 'checkout', 'guests'));
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'guests' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'special_requests' => 'nullable|string',
        ]);

        $villa = Villa::find($validated['villa_id']);
        
        // Get or create a room type for this villa
        $roomType = $villa->roomTypes()->first();
        if (!$roomType) {
            $roomType = $villa->roomTypes()->create([
                'name' => 'Standard',
                'capacity' => $villa->capacity,
                'price' => $villa->base_price,
                'rooms_count' => $villa->rooms_total,
                'status' => 'available',
            ]);
        }        

        // Calculate number of nights and ensure it's positive
        $checkin = \Carbon\Carbon::parse($validated['checkin']);
        $checkout = \Carbon\Carbon::parse($validated['checkout']);

        // Using abs() ensures that even if dates are swapped, nights are positive
        $nights = abs($checkout->diffInDays($checkin));

        // Calculate total price
        $totalPrice = $villa->base_price * $nights;


        // Get or create a user for the guest
        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => bcrypt('guest_' . uniqid()),
                'role' => 'guest',
            ]);
        }

        // Create booking
        $booking = Booking::create([
            'villa_id' => $villa->id,
            'villa_room_type_id' => $roomType->id,
            'user_id' => $user->id,
            'guest_name' => $validated['name'],
            'guest_email' => $validated['email'],
            'guest_phone' => $validated['phone'],
            'check_in_date' => $validated['checkin'],
            'check_out_date' => $validated['checkout'],
            'nights' => $nights,
            'guests' => $validated['guests'],
            'guest_count' => $validated['guests'],
            'special_requests' => $validated['special_requests'] ?? null,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'booking_code' => 'BK-' . strtoupper(uniqid()),
        ]);

        // Redirect to payment with booking ID
        return redirect()->route('guest.payment', ['booking_id' => $booking->id]);
    }

    public function payment($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('guest.payment', compact('booking'));
    }
}
