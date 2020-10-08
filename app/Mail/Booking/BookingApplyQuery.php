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

class BookingApplyQuery extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject = 'Вы встали в очередь на бронирование';

    public string $user_full_name;
    public string $service_name;
    public string $booking_type;

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
        $booking_type = BookingType::find($service_booking->booking_type_id);

        if ($booking_type->booking_type_name === 'Свободная') {
            $this->booking_type = 'свободный';
        } elseif ($booking_type->booking_type_name === 'Семейная') {
            $this->booking_type = 'семеный';
        }

        $this->service_name = $service->title;
        $this->subject .= ' услуги "' . $service->title . '"';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    final public function build()
    {
        return $this->view('mails.booking.apply-query');
    }
}
