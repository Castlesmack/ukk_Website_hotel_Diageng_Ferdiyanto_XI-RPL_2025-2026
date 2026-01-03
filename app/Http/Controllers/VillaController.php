<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villa;

class VillaController extends Controller
{
    public function search(Request $request)
    {
        $checkin = $request->get('checkin');
        $checkout = $request->get('checkout');
        $guests = $request->get('guests', 1);

        // For now, just return all villas since we don't have booking logic yet
        $villas = Villa::all();

        return view('guest.villa_search', compact('villas', 'checkin', 'checkout', 'guests'));
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
}
