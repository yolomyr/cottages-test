<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceBooking extends Model
{
    use HasFactory;

    protected $fillable = ['booking_type_id', 'service_id', 'available_days_interval'];

    public $timestamps = false;

    final public function schedules(): HasMany {
        return $this->hasMany(ServiceBookingSchedule::class)->orderBy('week_day');
    }

    final public static function getScheduleIntervals(ServiceBooking $service_booking): array {
        // -1 because of current day
        $interval = 0;
        if (isset($service_booking->available_days_interval)) {
            if ($service_booking->booking_type_id === BookingType::TYPES['family']) {
                $interval = (int) $service_booking->available_days_interval;
            } else {
                $interval = (int) $service_booking->available_days_interval - 1;
            }

            $dates = self::getScheduleDateInterval($interval);
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

            $dates = [
                'started_at' => Carbon::now()->isoWeekday($schedule_start->week_day)->toDateString(),
                'finished_at' => Carbon::now()->isoWeekday($schedule_end->week_day)->toDateString(),
            ];
        }

        return [
            'date' => $dates,
            'time' => ServiceBookingSchedule::getScheduleTimeIntervals($service_booking->id, $interval)
        ];
    }

    final public static function getScheduleDateInterval(int $interval): array {
        $start_booking_date = Carbon::now()->format('Y-m-d');
        $finish_booking_date = Carbon::now()->addDays($interval)->format('Y-m-d');

        return [
            'started_at' => $start_booking_date,
            'finished_at' => $finish_booking_date
        ];
    }

    final public static function getFirst(int $service_id, int $booking_type_id) {
        return self::where([
            ['booking_type_id', $booking_type_id],
            ['service_id', $service_id]
        ])->first();
    }
}
