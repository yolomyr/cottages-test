<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetUserRating extends Command
{
    public const DEFAULT_RATING = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:rating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets to 0 all users rating columns.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    final public function handle(): int
    {
        User::where('rating','!=', self::DEFAULT_RATING)->update([
            'rating' => self::DEFAULT_RATING
        ]);

        $this->info('User ratings reset');

        return 1;
    }
}
