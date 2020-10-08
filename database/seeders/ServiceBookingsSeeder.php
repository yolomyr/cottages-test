<?php

namespace Database\Seeders;

use App\Models\BookingType;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\ServiceBookingSchedule;
use App\Models\ServiceBookingScheduleType;
use Illuminate\Database\Seeder;

class ServiceBookingsSeeder extends Seeder
{
    public const PRESET = [
        'Бассейн' => [
            'Свободная' => [
                'booking_type_id' => 0,
                'service_id' => 0,
                'available_days_interval' => 7,
                // schedules
                'availability' => [
                    [
                        'started_at' => '10:00',
                        'finished_at' => '23:00',
                        'week_day' => null
                    ],
                    [
                        'started_at' => '10:00',
                        'finished_at' => '12:00',
                        'week_day' => 6
                    ],
                    [
                        'started_at' => '18:00',
                        'finished_at' => '23:00',
                        'week_day' => 6
                    ],
                    [
                        'started_at' => '10:00',
                        'finished_at' => '12:00',
                        'week_day' => 7
                    ],
                    [
                        'started_at' => '18:00',
                        'finished_at' => '23:00',
                        'week_day' => 7
                    ]
                ]
            ],
            'Семейная' => [
                'booking_type_id' => 0,
                'service_id' => 0,
                'available_days_interval' => null,
                'availability' => [
                    [
                        'started_at' => '13:00',
                        'finished_at' => '18:00',
                        'week_day' => 6
                    ],
                    [
                        'started_at' => '13:00',
                        'finished_at' => '18:00',
                        'week_day' => 7
                    ]
                ],
                'limitation' => [
                    [
                        'started_at' => '00:00',
                        'finished_at' => '23:59',
                        'week_day' => 1
                    ],
                    [
                        'started_at' => '00:00',
                        'finished_at' => '23:59',
                        'week_day' => 2
                    ],
                    [
                        'started_at' => '00:00',
                        'finished_at' => '23:59',
                        'week_day' => 3
                    ],
                    [
                        'started_at' => '00:00',
                        'finished_at' => '23:59',
                        'week_day' => 4
                    ],
                    [
                        'started_at' => '00:00',
                        'finished_at' => '15:00',
                        'week_day' => 5
                    ]
                ]
            ]
        ],
        'Тенистый корт' => [
            'Свободная' => [
                'booking_type_id' => 0,
                'service_id' => 0,
                'available_days_interval' => 1,
                'availability' => [
                    [
                        'started_at' => '10:00',
                        'finished_at' => '23:00',
                        'week_day' => null
                    ],
                ]
            ],
            'Семейная' => [
                'booking_type_id' => 0,
                'service_id' => 0,
                'available_days_interval' => 6,
                'availability' => [
                    [
                        'started_at' => '10:00',
                        'finished_at' => '23:00',
                        'week_day' => null
                    ],
                ],
                'limitation' => [
                    [
                        'started_at' => '00:00',
                        'finished_at' => '15:00',
                        'week_day' => -1
                    ]
                ]
            ]
        ],
        'Бильярд' => [
            'Свободная' => [
                'booking_type_id' => 0,
                'service_id' => 0,
                'available_days_interval' => 1,
                'availability' => [
                    [
                        'started_at' => '10:00',
                        'finished_at' => '23:00',
                        'week_day' => null
                    ],
                ],
            ],
            'Семейная' => [
                'booking_type_id' => 0,
                'service_id' => 0,
                'available_days_interval' => 6,
                'availability' => [
                    [
                        'started_at' => '10:00',
                        'finished_at' => '23:00',
                        'week_day' => null
                    ],
                ],
                'limitation' => [
                    [
                        'started_at' => '00:00',
                        'finished_at' => '15:00',
                        'week_day' => -1
                    ]
                ]
            ]
        ],
        'Кафе' => [
            'Семейная' => [
                'booking_type_id' => 0,
                'service_id' => 0,
                'available_days_interval' => 30,
                'availability' => [
                    [
                        'started_at' => '10:00',
                        'finished_at' => '23:00',
                        'week_day' => null
                    ],
                ],
                'limitation' => [
                    [
                        'started_at' => '00:00',
                        'finished_at' => '15:00',
                        'week_day' => -1
                    ]
                ]
            ]
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $booking_schedule_types = ServiceBookingScheduleType::all();
        $sevices = Service::all();
        $booking_types = BookingType::all();
        foreach ($sevices as $service) {
            foreach ($booking_types as $booking_type) {
                if (!isset(self::PRESET[$service->title][$booking_type->booking_type_name])) {
                    continue;
                }

                $preset = self::PRESET[$service->title][$booking_type->booking_type_name];
                $service_booking = ServiceBooking::create([
                    'booking_type_id' => $booking_type->id,
                    'service_id' => $service->id,
                    'available_days_interval' => $preset['available_days_interval'],
                ]);


                foreach ($booking_schedule_types as $schedule_type) {
                    if (!isset($preset[$schedule_type->schedule_type_name])) {
                        continue;
                    }

                    $preset_schedules = $preset[$schedule_type->schedule_type_name];

                    foreach ($preset_schedules as $schedule) {
                        ServiceBookingSchedule::create([
                            'service_booking_id' => $service_booking->id,
                            'schedule_type_id' => $schedule_type->id,
                            'started_at' => $schedule['started_at'],
                            'finished_at' => $schedule['finished_at'],
                            'week_day' => $schedule['week_day']
                        ]);
                    }
                }
            }
        }
    }
}
