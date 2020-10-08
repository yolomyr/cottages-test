<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject = 'Подтверждение аккаунта';
    public string $user_full_name;
    public string $user_password;
    public User $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $user_password
     */
    public function __construct(User $user, string $user_password)
    {
        $this->user_full_name = $user->getFullName();
        $this->user_password = $user_password;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.user-verify');
    }
}
