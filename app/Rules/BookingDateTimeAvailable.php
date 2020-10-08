<?php

namespace App\Rules;

use App\Models\BookingType;
use App\Models\ServiceBooking;
use App\Models\ServiceBookingScheduleType;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class BookingDateTimeAvailable implements Rule
{
    private int $service_id;
    private int $booking_type_id;
    private string $booking_date;
    private string $started_at;
    private string $finished_at;

    private string $message = 'Apply booking error, please try other date or time';

    /**
     * Create a new rule instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->service_id = $attributes['service_id'];
        $this->booking_type_id = $attributes['booking_type_id'];
        $this->booking_date = $attributes['booking_date'];
        $this->started_at = $attributes['started_at'];
        $this->finished_at = $attributes['finished_at'];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    final public function passes($attribute, $value): bool
    {
        /**
         * Common vars
         */
        $service_booking = ServiceBooking::getFirst($this->service_id, $this->booking_type_id);

        if ($service_booking === null) {
            return false;
        }

        $service_booking->schedules;
        $schedules = $service_booking
            ->schedules
            ->groupBy(['schedule_type_id', 'week_day'])
            ->toArray();

        $availability_id = ServiceBookingScheduleType::TYPES['availability'];
        $limitation_id = ServiceBookingScheduleType::TYPES['limitation'];

        $booking_date = Carbon::createFromFormat('Y-m-d', $this->booking_date);
        $booking_day_of_week = $booking_date->dayOfWeekIso;

        /**
         * Check if booking date in working days interval
         */
        if (!empty($schedules[$availability_id][''])) {
            // booking date in date intervals
            $interval = (int) $service_booking->available_days_interval;

            if ($service_booking->booking_type_id === BookingType::TYPES['family']) {
                $start_interval = Carbon::now()->addDay()->toDateString();
                $end_interval = Carbon::now()->addDays($interval + 1)->toDateString();
            } else {
                $start_interval = Carbon::now()->toDateString();
                $end_interval = Carbon::now()->addDays($interval)->toDateString();
            }


            $in_date_interval = $booking_date->between($start_interval, $end_interval);
            $only_current_day = $this->booking_date === $start_interval && $start_interval === $end_interval && $interval === 1;

            if (!$in_date_interval && !$only_current_day) {
                $this->message = __('validation.booking.out_of_date');
                return false;
            }
        } elseif (empty($schedules[$availability_id][$booking_day_of_week])) {
            $this->message = __('validation.booking.out_of_date');
            return false;
        }

        /**
         * Check if booking time in working time intervals
         */
        // 1 (for Monday) through 7 (for Sunday)
        $booking_started_at = Carbon::createFromTimeString($this->started_at);
        $booking_finished_at = Carbon::createFromTimeString($this->finished_at);

        $in_interval = false;
        $schedule_key = (!empty($schedules[$availability_id][$booking_day_of_week])) ?
            $booking_day_of_week :
            '';

        foreach ($schedules[$availability_id][$schedule_key] as $time_schedule) {
            $in_interval = $booking_started_at->between($time_schedule['started_at'], $time_schedule['finished_at']) &&
                $booking_finished_at->between($time_schedule['started_at'], $time_schedule['finished_at']);

            if ($in_interval) {
                break;
            }
        }

        if (!$in_interval) {
            $this->message = __('validation.booking.out_of_working_time');
            return false;
        }

        /**
         * Check if now time in booking limitation datetime intervals
         */

        if (!empty($schedules[$limitation_id]) && $this->booking_type_id === BookingType::TYPES['family']) {
            // 1 (for Monday) through 7 (for Sunday)
            $now = Carbon::now();
            $now_day_of_week = $now->dayOfWeekIso;

            $in_interval = false;

            $limitation_schedules = '';

            $limitation_key = (!empty($schedules[$limitation_id][$now_day_of_week])) ?
                $now_day_of_week :
                -1;

            if (isset($schedules[$limitation_id][$limitation_key])) {
                $limitation_array = $schedules[$limitation_id][$limitation_key];

                foreach ($limitation_array as $time_limitation) {
                    if ($limitation_key < 0) {
                        // if date today, cant reserve booking
                        if ($booking_date->toDateString() === $now->toDateString()) {
                            $this->message = __('validation.booking.out_of_limitation_time', ['schedule' => $limitation_schedules]);
                            return false;
                        } else {
                            $in_interval = true;
                        }

                        if ($now->toDateString() === $booking_date->subDays(abs($time_limitation['week_day']))->toDateString()) {
                            $in_interval = $now->between($time_limitation['started_at'], $time_limitation['finished_at']);
                        }
                    } else {
                        $in_interval = Carbon::now()->between($time_limitation['started_at'], $time_limitation['finished_at']);
                    }

                    if ($in_interval) {
                        break;
                    }
                }
            }

            foreach ($schedules[$limitation_id] as $day => $limitations) {
                if ($day >= 0) {
                    $limitation_schedules .= Carbon::now()->next($day)->isoFormat('dd') . ' - ';
                } else {
                    $limitation_schedules .= 'за ' . abs($day) . ' день/дня до даты бронирования ';
                }

                foreach ($limitations as $limitation) {
                    $limitation_schedules .= 'с ' . Carbon::createFromTimeString($limitation['started_at'])->isoFormat('H:mm') . ' до ' . Carbon::createFromTimeString($limitation['finished_at'])->isoFormat('H:mm') . ', ';
                }
            }

            $limitation_schedules = rtrim($limitation_schedules, ', ');

            if (!$in_interval) {
                $this->message = __('validation.booking.out_of_limitation_time', ['schedule' => $limitation_schedules]);
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    final public function message(): string
    {
        return $this->message;
    }
}
