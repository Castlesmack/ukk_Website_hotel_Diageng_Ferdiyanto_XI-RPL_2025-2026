<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->timestamp = now();
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin.orders'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'order.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->booking->id,
            'guest_name' => $this->booking->guest_name ?? $this->booking->user->name ?? 'Guest',
            'villa_id' => $this->booking->villa_id,
            'villa_name' => $this->booking->villa->name,
            'check_in' => $this->booking->check_in_date->format('d M Y'),
            'check_out' => $this->booking->check_out_date->format('d M Y'),
            'total_price' => number_format($this->booking->total_price, 0, ',', '.'),
            'status' => $this->booking->status,
            'created_at' => $this->booking->created_at->format('H:i:s'),
            'timestamp' => $this->timestamp->toIso8601String(),
        ];
    }
}
