<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserPasswordRestore extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public const ROUTE = '/password/reset';

    public $subject = 'Восстановление пароля';
    public string $user_full_name;
    public string $restore_url;
    public User $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        $route = self::ROUTE . '?' . $token;

        $this->user_full_name = $user->getFullName();
        $this->user = $user;
        $this->restore_url = env('APP_URL') . $route;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.password-restore');
    }
}
