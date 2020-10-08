<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserChangeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public const ROUTE = '/api/change/email';

    public $subject = 'Подтверждение смены почты';
    public string $user_full_name;
    public string $user_new_email;
    public string $restore_url;
    public User $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $new_email
     * @param string $token
     */
    public function __construct(User $user, string $new_email, string $token)
    {
        $route = self::ROUTE . '?id=' . $user->id . '&token=' . $token;

        $this->user_full_name = $user->getFullName();
        $this->user_new_email = $new_email;
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
        return $this->view('mails.email-change');
    }
}
