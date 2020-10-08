<?php

namespace App\Models;

use App\Events\BookingRated;
use App\Mail\Admin\CancelAllBookings;
use App\Mail\Booking\BookingApplyQuery;
use App\Mail\Booking\BookingVerify;
use App\Traits\Caster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Booking extends Model
{
    use HasFactory, Caster, SoftDeletes;

    public const VERIFIED_STATUS = true;
    public const UNVERIFIED_STATUS = false;

    protected $fillable = ['user_id', 'service_booking_id', 'booking_date', 'started_at', 'finished_at', 'people_number', 'commentary', 'verified', 'deleted_by_admin', 'cancel_reason'];

    protected $casts = [
        'booked_date' => 'date:Y-m-d',
        'started_at' => 'date:H:i',
        'finished_at' => 'date:H:i',
        'verified' => 'boolean',
        'deleted_by_admin' => 'boolean'
    ];

    /**
     * Get service_booking record associated with the booking.
     */
    final public function service_booking() {
        return $this->hasOne(ServiceBooking::class, 'id', 'service_booking_id');
    }

    /**
     * Get user record associated with the booking.
     */
    final public function user(): HasOne {
        return $this->hasOne(User::class, 'id', 'user_id')->select(['name', 'surname', 'email', 'rating']);
    }

    final public static function getAllBookings(int $service_id, int $booking_type_id): array {
        $service_booking = ServiceBooking::getFirst($service_id, $booking_type_id);
        $user = auth()->user();

        $current_user = [];

        if ($user !== null) {
            $current_user = [
                'name' => $user->name,
                'surname' => $user->surname,
                'rating' => $user->rating
            ];
        }

        return [
            'schedule' => ServiceBooking::getScheduleIntervals($service_booking),
            'booked' => self::getBooked($service_booking),
            'current_user' => $current_user
        ];
    }

    final public static function getBooked(ServiceBooking $service_booking) {
        $user = auth()->user();

        if ($user === null) {
            return (object) [];
        }

        $bookings = self::where('service_booking_id', $service_booking->id);

        if (isset($service_booking->available_days_interval)) {
            if ($service_booking->booking_type_id === BookingType::TYPES['family']) {
                $interval = (int) $service_booking->available_days_interval;
            } else {
                $interval = (int) $service_booking->available_days_interval - 1;
            }
            $bookings->whereRaw('booking_date >= DATE(NOW()) AND booking_date <= DATE(NOW() + INTERVAL ' . $interval . ' DAY)');
        } else {
            $schedule_start = ServiceBookingSchedule::where('service_booking_id', $service_booking->id)
                ->where('schedule_type_id', ServiceBookingScheduleType::TYPES['availability'])
                ->whereNotNull('week_day')
                ->orderBy('week_day')
                ->first();

            $schedule_end = ServiceBookingSchedule::where('service_booking_id', $service_booking->id)
                ->where('schedule_type_id', ServiceBookingScheduleType::TYPES['availability'])
                ->whereNotNull('week_day')
                ->orderBy('week_day', 'desc')
                ->first();

            $bookings = self::where('service_booking_id', $service_booking->id)
                ->whereRaw('(booking_date = "' . Carbon::now()->isoWeekday($schedule_start->week_day)->toDateString() . '" OR booking_date = "' . Carbon::now()->isoWeekday($schedule_end->week_day)->toDateString() . '")');
        }

        $bookings = $bookings->whereNull('deleted_at')->get();

        if (count($bookings) === 0) {
            return (object) [];
        }

        foreach ($bookings as $booking) {
            $booking->user;
        }

        return $bookings->groupBy('booking_date');
    }

    final public function setVerifiedIfFree(): void {
        $this->verified = $this->isFreeInterval();
    }

    final public function setVerified(bool $value = true): void {
        $this->verified = $value;
        $this->save();
    }

    /**
     * Booking post create function. Based on booking_type_id
     * @param array $booking_data
     */
    final public static function createPost(array $booking_data): void {
        $user = auth()->user();
        $service_booking = ServiceBooking::getFirst($booking_data['service_id'], $booking_data['booking_type_id']);

        $is_free_booking = $service_booking->booking_type_id === BookingType::TYPES['free'];
        $booking_data['user_id'] = $user->id;
        $booking_data['service_booking_id'] = $service_booking->id;

        $booking = new self($booking_data);
        if ($is_free_booking) {
            $booking->setVerifiedIfFree();
        }

        $booking->save();

        $mail = null;

        if ($booking->verified && $is_free_booking) {
            $mail = new BookingVerify($user, $booking);
        } else {
            $mail = new BookingApplyQuery($user, $booking);
        }

        Mail::to($user->email)->send($mail);
    }

    /**
     * Cancel booking post by user
     * @param array $booking_data
     * @throws Throwable
     */
    final public function cancel(array $booking_data): void {
        $service_booking = ServiceBooking::find($this->service_booking_id);

        if ($service_booking->booking_type_id === BookingType::TYPES['free']) {
            $next_order_booking = self::where([
                ['booking_date', $this->booking_date],
                ['service_booking_id', $this->service_booking_id],
                ['id', '!=', $booking_data['booking_id']]
            ])
                ->whereRaw('( (started_at BETWEEN "' . $this->started_at . '" and "' . $this->finished_at . '") OR (finished_at BETWEEN "' . $this->started_at . '" and "' . $this->finished_at . '" ) )')
                ->orderBy('created_at', 'asc')->first();

            if (!empty($next_order_booking)) {
                $next_order_booking->setVerified();

                $user = User::find($next_order_booking->user_id);
                Mail::to($user->email)->send(new BookingVerify($user, $next_order_booking));
            }
        }

        // deleted by user
        if (isset($booking_data['cancel_reason'])) {
            $this->cancel_reason = $booking_data['cancel_reason'];
        }
        $this->deleted_by_admin = false;
        $this->save();

        $this->delete();

        if ($service_booking->booking_type_id === BookingType::TYPES['family']) {
            event(new BookingRated($this));
        }
    }

    /**
     * Check if booked any users on this date and chosen time interval
     * @return bool
     */
    final public function isFreeInterval(): bool {
        $exists_interval = self::where([
            ['booking_date', $this->booking_date],
            ['service_booking_id', $this->service_booking_id],
        ])
            ->whereRaw('( (started_at BETWEEN "' . $this->started_at . '" and "' . $this->finished_at . '") OR (finished_at BETWEEN "' . $this->started_at . '" and "' . $this->finished_at . '" ) )');

        // if its db record - ignore record id
        if (isset($this->id)) {
            $exists_interval->where('id', '!=', $this->id);
        }

        return !$exists_interval->exists();
    }

    final public function approve(): void {
        $this->setVerified();
        event(new BookingRated($this));

        $user = User::find($this->user_id);
        Mail::to($user->email)->send(new BookingVerify($user, $this));
    }

    final public function unApprove(): void {
        $this->verified = self::UNVERIFIED_STATUS;
        $this->save();
    }

    final public static function cancelBookings(Service $service): void {
        $service_booking = ServiceBooking::getFirst($service->id, ServiceStatus::STATUSES['inactive']);
        $bookings = self::where('service_booking_id', $service_booking->id)
            ->whereNull('deleted_at')
            ->whereRaw('booking_date >= CURDATE()')
            ->get();

        foreach ($bookings as $booking) {
            $user = User::find($booking->user_id);

            $booking->deleted_by_admin = true;
            $booking->save();

            $booking->delete();

            if ($service_booking->booking_type_id === BookingType::TYPES['family']) {
                event(new BookingRated($booking));
            }

            Mail::to($user->email)->send(new CancelAllBookings($user, $service, $booking));
        }
    }

    /**
     * Get users bookings
     * @param int $service_id
     * @param int $user_id
     * @return array[]
     */
    final public static function getBookings(int $service_id = 0, int $user_id = 0): array {
        $bookings_request = Service::selectRaw('services.title, services.unavailability_reason, service_bookings.service_id, service_bookings.booking_type_id, bookings.*, CONCAT(users.name, " ", users.surname) as full_name, users.rating, (bookings.deleted_at IS NOT NULL) as is_inactive')
            ->join('service_bookings', 'service_bookings.service_id', 'services.id')
            ->join('bookings', 'bookings.service_booking_id', 'service_bookings.id')
            ->join('users', 'bookings.user_id', 'users.id')
            ->whereRaw('bookings.booking_date >= CURDATE()')
            ->orderBy('bookings.booking_date')
            ->orderBy('service_bookings.booking_type_id');

        if (!empty($service_id)) {
            $bookings_request->where('services.id', $service_id);
        }

        if (!empty($user_id)) {
            $bookings_request->where('user_id', $user_id);
        }

        $bookings = $bookings_request->get();

        $grouped = [];

        foreach ($bookings as $booking) {
            $booking->created_at_string = self::castBookingCreatedToHuman($booking->created_at);

            if (!empty($booking->deleted_at)) {
                $booking->deleted_at = self::castBookingCreatedToHuman($booking->deleted_at);
                if ($booking->deleted_by_admin) {
                    $booking->cancel_reason = $booking->unavailability_reason;
                }
            }

            $user_estates = UserEstate::where('user_id', $booking->user_id)->get()->toArray();

            foreach ($user_estates as $user_estates_key => $estate) {
                $user_estates[$user_estates_key]['title'] = UserEstateType::SHORT_TYPES[$estate['estate_type_id']];
            }

            $grouped[] = [
                'booking_id' => $booking->id,
                'booking_date_string' => BookingType::TYPES_NAMES[$booking->booking_type_id] . ', ' . self::castBookingCreatedToHuman($booking->booking_date, true),
                'booking_date' => $booking->booking_date,
                'full_name' => $booking->full_name,
                'started_at' => Carbon::createFromTimeString($booking->started_at)->format('H:i'),
                'finished_at' => Carbon::createFromTimeString($booking->finished_at)->format('H:i'),
                'is_inactive' => $booking->is_inactive,
                'service_id' => $booking->service_id,
                'deleted_by_admin' => $booking->deleted_by_admin,
                'created_at_string' => $booking->created_at_string,
                'created_at' => Carbon::createFromTimeString($booking->created_at)->format('Y-m-d'),
                'deleted_at' => $booking->deleted_at,
                'cancel_reason' => $booking->cancel_reason,
                'user_estates' => $user_estates,
                'booking_type_id' => $booking->booking_type_id,
                'people_number' => $booking->people_number,
                'commentary' => $booking->commentary,
                'rating' => $booking->rating,
                'verified' => $booking->verified
            ];
        }

        return $grouped;
    }
}
