<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VillaAvailabilityChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $villa;
    public $checkInDate;
    public $checkOutDate;
    public $isAvailable;

    /**
     * Create a new event instance.
     */
    public function __construct($villa, $checkInDate, $checkOutDate, $isAvailable = true)
    {
        $this->villa = $villa;
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
        $this->isAvailable = $isAvailable;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('villa-availability'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'villa_id' => $this->villa->id,
            'villa_name' => $this->villa->name,
            'check_in_date' => $this->checkInDate,
            'check_out_date' => $this->checkOutDate,
            'is_available' => $this->isAvailable,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get the name of the event broadcast.
     */
    public function broadcastAs(): string
    {
        return 'villa.availability_changed';
    }
}
