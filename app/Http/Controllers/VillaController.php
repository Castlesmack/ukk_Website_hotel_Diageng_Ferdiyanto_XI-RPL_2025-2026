<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villa;
use App\Models\Booking;
use App\Models\User;
use App\Models\HomepageSetting;
use App\Models\VillaVisibility;
use App\Models\HomepageFacility;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\Auth;

class VillaController extends Controller
{
    public function index()
    {
        $homepage = HomepageSetting::first();
        $sliderImages = $homepage?->slider_images ?? [];
        $description = $homepage?->description ?? '';
        
        // Get visible villas in order
        $visibleVillaIds = VillaVisibility::where('is_visible', true)->orderBy('order')->pluck('villa_id');
        
        // Fetch villas and sort by the visibility order (SQLite doesn't support FIELD function)
        if ($visibleVillaIds->isEmpty()) {
            // If no visibility records exist, show all villas
            $villas = Villa::all();
        } else {
            $villas = Villa::whereIn('id', $visibleVillaIds)->get();
            // Sort by the order in visibleVillaIds
            $villas = $villas->sortBy(function($villa) use ($visibleVillaIds) {
                return $visibleVillaIds->search($villa->id);
            })->values();
        }
        
        // Get visible facilities
        $facilities = HomepageFacility::where('is_visible', true)->orderBy('category')->orderBy('order')->get();
        
        return view('guest.homepage', compact('villas', 'sliderImages', 'description', 'facilities'));
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

        // Get homepage settings for slider and description
        $homepage = HomepageSetting::first();
        $sliderImages = $homepage?->slider_images ?? [];
        $description = $homepage?->description ?? '';
        
        // Get visible facilities
        $facilities = HomepageFacility::where('is_visible', true)->orderBy('category')->orderBy('order')->get();

        return view('guest.home', compact('villas', 'checkin', 'checkout', 'guests', 'sliderImages', 'description', 'facilities'));
    }

    public function detail($id)
    {
        $villa = Villa::findOrFail($id);
        
        // Get all confirmed/pending bookings for this villa
        $bookedDates = Booking::where('villa_id', $id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->selectRaw('check_in_date, check_out_date')
            ->get();
        
        return view('guest.villa_detail', compact('villa', 'bookedDates'));
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
        
        // Check villa status (support both old and new status values)
        $inactiveStatuses = ['inactive', 'unavailable', 'maintenance'];
        if (in_array($villa->status, $inactiveStatuses)) {
            return redirect()->back()
                ->withErrors(['villa' => 'Villa is not available for booking at this time.'])
                ->withInput();
        }
        
        // Check if villa is available for the requested dates
        $checkin = \Carbon\Carbon::parse($validated['checkin']);
        $checkout = \Carbon\Carbon::parse($validated['checkout']);
        
        $existingBooking = Booking::where('villa_id', $validated['villa_id'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->where(function($query) use ($checkin, $checkout) {
                $query->whereBetween('check_in_date', [$checkin, $checkout->subDay()])
                      ->orWhereBetween('check_out_date', [$checkin->addDay(), $checkout])
                      ->orWhere(function($q) use ($checkin, $checkout) {
                          $q->where('check_in_date', '<=', $checkin)
                            ->where('check_out_date', '>=', $checkout);
                      });
            })
            ->first();
        
        if ($existingBooking) {
            return redirect()->back()
                ->withErrors(['availability' => 'Villa ini tidak tersedia untuk tanggal yang dipilih. Silakan pilih tanggal lain.'])
                ->withInput();
        }
        
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

        // Broadcast real-time order creation to admin
        broadcast(new OrderCreated($booking))->toOthers();

        // Redirect to payment with booking ID
        return redirect()->route('guest.payment', ['booking_id' => $booking->id]);
    }

    public function payment($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('guest.payment', compact('booking'));
    }

    public function searchAPI(Request $request)
    {
        $capacity = $request->get('capacity');
        $dates = $request->get('dates');
        $price = $request->get('price');

        $query = Villa::query();

        // Filter by capacity
        if ($capacity) {
            $query->where('capacity', '>=', $capacity);
        }

        // Filter by price
        if ($price) {
            $query->where('base_price', '<=', $price);
        }

        // Filter by availability on selected date
        if ($dates) {
            // Get all bookings for the selected date
            $bookedVillaIds = Booking::where(function($q) use ($dates) {
                $q->where('check_in_date', '<=', $dates)
                  ->where('check_out_date', '>', $dates);
            })
            ->whereIn('status', ['confirmed', 'pending'])
            ->distinct()
            ->pluck('villa_id');

            // Exclude booked villas
            if ($bookedVillaIds->count() > 0) {
                $query->whereNotIn('id', $bookedVillaIds);
            }
        }

        $villas = $query->get();

        return response()->json([
            'villas' => $villas->map(function($villa) {
                return [
                    'id' => $villa->id,
                    'name' => $villa->name,
                    'capacity' => $villa->capacity,
                    'base_price' => $villa->base_price,
                    'image_url' => $villa->image_url,
                ];
            })
        ]);
    }
}
