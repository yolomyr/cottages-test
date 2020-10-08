<?php

namespace App\Mail\Admin;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Traits\Caster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelAllBookings extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, Caster;

    public $subject = 'Отмена заявок для сервиса';

    /**
     * @var Service
     */
    public Service $service;

    /**
     * @var Booking
     */
    public Booking $booking;

    public string $user_full_name;
    public string $booking_date;
    public string $unavailability_reason;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Service $service
     * @param Booking $booking
     */
    public function __construct(User $user, Service $service, Booking $booking)
    {
        $this->booking_date = self::castBookingCreatedToHuman($booking->booking_date, true);
        $this->service = $service;
        $this->unavailability_reason = $service->unavailability_reason;
        $this->booking = $booking;
        $this->user_full_name = $user->getFullName();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    final public function build()
    {
        return $this->view('mails.admin.cancel-bookings');
    }
}
