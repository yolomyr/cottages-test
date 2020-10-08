<?php

namespace App\Mail\Booking;

use App\Models\Booking;
use App\Models\BookingType;
use App\Models\Service;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingVerify extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Ваша бронь';

    public string $user_full_name;
    public string $service_name;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Booking $booking
     */
    public function __construct(User $user, Booking $booking)
    {
        $this->user_full_name = $user->getFullName();

        $service_booking = $booking->service_booking;
        $service = Service::find($service_booking->service_id);
        $this->service_name = $service->title;
        $this->subject .= ' услуги "' . $service->title . '" подтверждена';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.booking.verify');
    }
}
