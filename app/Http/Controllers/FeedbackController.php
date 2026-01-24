<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Show feedback list for current user
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin' || $user->role === 'receptionist') {
            // Admin/Receptionist see all feedbacks
            $feedbacks = Feedback::with(['user', 'responder', 'booking'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Guests see only their own feedbacks
            $feedbacks = Feedback::where('user_id', $user->id)
                ->with(['responder', 'booking'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('feedback.index', compact('feedbacks'));
    }

    /**
     * Show create feedback form
     */
    public function create()
    {
        $bookings = null;
        $user = Auth::user();
        
        // If guest, get their bookings for association
        if ($user->role === 'guest') {
            $bookings = $user->bookings()->get();
        }
        
        return view('feedback.create', compact('bookings'));
    }

    /**
     * Store feedback in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'message' => 'required|string|min:10|max:5000',
            'channel' => 'required|in:web,livechat,email',
        ]);

        $feedback = Feedback::create([
            'user_id' => Auth::id(),
            'booking_id' => $validated['booking_id'] ?? null,
            'message' => $validated['message'],
            'channel' => $validated['channel'],
            'status' => 'open',
        ]);

        return redirect()->route('feedback.show', $feedback->id)
            ->with('success', 'Feedback submitted successfully. We will respond soon!');
    }

    /**
     * Show single feedback detail
     */
    public function show(Feedback $feedback)
    {
        $user = Auth::user();
        
        // Check authorization
        if ($user->role === 'guest' && $feedback->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('feedback.show', compact('feedback'));
    }

    /**
     * Show edit form (for admins/receptionists to respond)
     */
    public function edit(Feedback $feedback)
    {
        $user = Auth::user();
        
        // Only admin/receptionist can respond
        if ($user->role !== 'admin' && $user->role !== 'receptionist') {
            abort(403, 'Unauthorized');
        }

        return view('feedback.edit', compact('feedback'));
    }

    /**
     * Update feedback with response
     */
    public function update(Request $request, Feedback $feedback)
    {
        $user = Auth::user();
        
        // Only admin/receptionist can respond
        if ($user->role !== 'admin' && $user->role !== 'receptionist') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'response' => 'required|string|min:5|max:5000',
            'status' => 'required|in:open,answered,closed',
        ]);

        $feedback->update([
            'response' => $validated['response'],
            'status' => $validated['status'],
            'responder_id' => $user->id,
        ]);

        return redirect()->route('feedback.show', $feedback->id)
            ->with('success', 'Feedback response submitted successfully!');
    }

    /**
     * Close feedback ticket
     */
    public function close(Feedback $feedback)
    {
        $user = Auth::user();
        
        // Guest can close their own, admin/receptionist can close any
        if ($user->role === 'guest' && $feedback->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $feedback->update(['status' => 'closed']);

        return redirect()->route('feedback.show', $feedback->id)
            ->with('success', 'Feedback closed.');
    }

    /**
     * Get feedback stats for dashboard
     */
    public function stats()
    {
        $user = Auth::user();
        
        $query = Feedback::query();
        
        if ($user->role === 'guest') {
            $query->where('user_id', $user->id);
        }

        $stats = [
            'total' => $query->count(),
            'open' => $query->open()->count(),
            'answered' => $query->answered()->count(),
            'closed' => $query->closed()->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get recent feedbacks (for dashboard widgets)
     */
    public function recent($limit = 5)
    {
        $user = Auth::user();
        
        $query = Feedback::with(['user', 'responder']);
        
        if ($user->role === 'guest') {
            $query->where('user_id', $user->id);
        }

        $feedbacks = $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($feedbacks);
    }
}
