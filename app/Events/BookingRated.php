<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\User;
use App\Models\BookingType;
use App\Models\ServiceBooking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingRated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Booking $booking;
    public ServiceBooking $service_booking;
    public bool $is_rating_based = false;
    public User $user;

    /**
     * Create a new event instance.
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->service_booking = ServiceBooking::find($booking->service_booking_id);
        $this->user = User::find($booking->user_id);

        $this->is_rating_based = $this->service_booking->booking_type_id === BookingType::TYPES['family'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('booking-rated');
    }
}
