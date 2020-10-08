<?php

namespace App\Console\Commands;

use App\Events\BookingRated;
use App\Mail\Booking\BookingVerify;
use App\Models\Booking;
use App\Models\BookingType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Collection;

class ApproveBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:approve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public int $approve_booking_type;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->approve_booking_type = BookingType::TYPES['family'];
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    final public function handle(): int
    {

        $bookings = Booking::select('bookings.*', 'users.rating', 'service_bookings.booking_type_id')
            ->join('users', 'bookings.user_id', 'users.id')
            ->join('service_bookings', 'bookings.service_booking_id', 'service_bookings.id')
            ->where('booking_type_id', $this->approve_booking_type)
            ->where('booking_date', Carbon::now()->toDateString())
            ->whereNull('bookings.deleted_at')
            ->orderBy('rating')
            ->orderBy('created_at')
            ->orderBy('started_at')
            ->get();

        if (count($bookings) <= 0) {
            $this->info('Bookings to approve not found');
            return 0;
        }

        $booking_started_at = Carbon::createFromTimeString($bookings[0]->started_at)->addMinutes(1);
        $booking_finished_at = Carbon::createFromTimeString($bookings[0]->finished_at);
        $booking_iterator = 0;
        foreach ($bookings as $booking_key => $booking) {
            $not_verified = $booking->verified === null;

            if ($booking_iterator === 0) {
                $booking_iterator++;

                if ($not_verified) {
                    $booking->approve();
                }

                continue;
            }

            $current_booking_started_at = Carbon::createFromTimeString($booking->started_at)->addMinutes(1);
            $current_booking_finished_at = Carbon::createFromTimeString($booking->finished_at);
            $in_interval = self::inInterval($booking_started_at, $booking_finished_at, $current_booking_started_at, $current_booking_finished_at);

            if ($not_verified) {
                if (!$in_interval) {
                    $booking_started_at = $current_booking_started_at;
                    $booking_finished_at = $current_booking_finished_at;
                    $booking->approve();

                    self::unApproveIntersection($bookings, $booking_key, $booking_started_at, $booking_finished_at);
                } else {
                    $booking->unApprove();
                }
            }

            $booking_iterator++;
        }

        $this->info('Bookings approved');
        return 1;
    }

    final public static function unApproveIntersection(Collection $bookings, int $start_from_key, Carbon $booking_started_at, Carbon $booking_finished_at): void {
        foreach ($bookings as $booking_key => $booking) {
            if ($booking_key <= $start_from_key) {
                continue;
            }

            $not_verified = $booking->verified === null;

            $current_booking_started_at = Carbon::createFromTimeString($booking->started_at);
            $current_booking_finished_at = Carbon::createFromTimeString($booking->finished_at);

            $in_interval = self::inInterval($booking_started_at, $booking_finished_at, $current_booking_started_at, $current_booking_finished_at);

            if ($not_verified && $in_interval) {
                $booking->unApprove();
            }
        }
    }

    final public static function inInterval(Carbon $booking_started_at, Carbon $booking_finished_at, Carbon $current_booking_started_at, Carbon $current_booking_finished_at): bool {
        return $current_booking_started_at->between($booking_started_at, $booking_finished_at) ||
            $current_booking_finished_at->between($booking_started_at, $booking_finished_at) ||
            $booking_started_at->between($current_booking_started_at, $current_booking_finished_at) ||
            $booking_finished_at->between($current_booking_started_at, $current_booking_finished_at);
    }
}
