<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Villa;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('status', 'confirmed');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('villa_id')) {
            $query->where('villa_id', $request->villa_id);
        }

        $bookings = $query->with('villa')->get();
        $totalIncome = $bookings->sum('total_price');
        $villas = Villa::all();

        $incomeByVilla = $bookings->groupBy('villa_id')
            ->map(fn($group) => $group->sum('total_price'));

        return view('admin.finances.index', compact(
            'bookings',
            'totalIncome',
            'villas',
            'incomeByVilla'
        ));
    }
}
