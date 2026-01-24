<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bookingId;
    public $guestName;
    public $villaName;
    public $oldStatus;
    public $newStatus;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($bookingId, string $guestName, string $villaName, string $oldStatus, string $newStatus)
    {
        $this->bookingId = $bookingId;
        $this->guestName = $guestName;
        $this->villaName = $villaName;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->timestamp = now();
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin.orders'),
            new Channel("order.{$this->bookingId}"),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'order.status.changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->bookingId,
            'guest_name' => $this->guestName,
            'villa_name' => $this->villaName,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status_label' => ucfirst($this->newStatus),
            'timestamp' => $this->timestamp->toIso8601String(),
        ];
    }
}
