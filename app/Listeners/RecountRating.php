<?php

namespace App\Listeners;

use App\Events\BookingRated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecountRating
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BookingRated $rated
     * @return void
     */
    public function handle(BookingRated $rated)
    {
        if (!$rated->is_rating_based) {
            return;
        }

        // if record not deleted increment rating
        if ((int) $rated->booking->verified === 1) {
            if ($rated->booking->exists) {
                $rated->user->increaseRating();
            } else {
                $rated->user->decreaseRating();
            }
        }
    }
}
