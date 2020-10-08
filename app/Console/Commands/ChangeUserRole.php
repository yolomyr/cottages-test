<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ChangeUserRole extends Command
{
    public const DEFAULT_ROLE = 'admin';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:role {user_id} {--role=' . self::DEFAULT_ROLE . '}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change user role. ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->description .= 'Parameters for role option: ';
        foreach (User::ROLES as $role_key => $role) {
            $this->description .= $role_key . ',';
        }
        $this->description = rtrim($this->description, ',');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    final public function handle(): int
    {
        $user_id = $this->argument('user_id');
        $role_option = $this->option('role');

        if (!isset(User::ROLES[ $role_option ])) {
            return 0;
        }

        $role_id = User::ROLES[ $role_option ];

        User::findOrFail($user_id)->setRole($role_id);

        return 1;
    }
}
