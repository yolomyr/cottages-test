<?php

namespace App\Models;

use App\Traits\Caster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBookingSchedule extends Model
{
    use HasFactory, Caster;

    protected $fillable = ['service_booking_id', 'schedule_type_id', 'started_at', 'finished_at', 'week_day'];

    public $timestamps = false;

    final public static function getScheduleTimeIntervals(int $service_booking_id, int $interval): array {
        $schedule_groups = self::selectRaw('*, HOUR(started_at) as started_at_hours, MINUTE(started_at) as started_at_minutes, HOUR(finished_at) as finished_at_hours, MINUTE(finished_at) as finished_at_minutes')
            ->where('service_booking_id', $service_booking_id)
            ->where('schedule_type_id', ServiceBookingScheduleType::TYPES['availability'])
            ->get()
            ->groupBy('week_day');

        $time_intervals = [];


        if (isset($schedule_groups[''])) {
            for ($day = 0; $day <= $interval; $day++) {
                $formatted_date = self::getFormattedDate( Carbon::now()->addDays($day) );

                if (isset($time_intervals[ $formatted_date ])) {
                    continue;
                }

                self::setTimeIntervals($time_intervals, $schedule_groups[''][0], $formatted_date);
            }
        }

        foreach ($schedule_groups as $week_day => $schedule_group) {
            if (empty($week_day)) {
                continue;
            }

            $formatted_date = self::getFormattedDate( Carbon::now()->isoWeekday($week_day) );
            $time_intervals[$formatted_date] = [];

            foreach ($schedule_group as $schedule) {
                self::setTimeIntervals($time_intervals, $schedule, $formatted_date);
            }
        }

        return $time_intervals;
    }

    public static function setTimeIntervals(array &$time_intervals, object $time_interval, string $date_key): void {
        $time_intervals[ $date_key ][] = [
            'started_at' => [
                'hours' => $time_interval->started_at_hours,
                'minutes' => $time_interval->started_at_minutes
            ],
            'finished_at' => [
                'hours' => $time_interval->finished_at_hours,
                'minutes' => $time_interval->finished_at_minutes
            ]
        ];
    }
}
