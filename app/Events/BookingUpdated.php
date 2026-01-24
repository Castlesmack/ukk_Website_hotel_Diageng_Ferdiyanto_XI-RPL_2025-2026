<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($booking, $message = null)
    {
        $this->booking = $booking;
        $this->message = $message ?? 'Booking updated';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('bookings'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'booking_id' => $this->booking->id,
            'status' => $this->booking->status,
            'message' => $this->message,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get the name of the event broadcast.
     */
    public function broadcastAs(): string
    {
        return 'booking.updated';
    }
}
