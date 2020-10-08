<?php

namespace App\Mail\Admin;

use App\Models\User;
use App\Models\UserEstateType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public const ROUTE = '/api/verify/user';

    public $subject = 'Оповещение о регистрации пользователя';
    public User $user;
    public array $estate_types;
    public string $confirmation_url;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        $route = self::ROUTE . '?id=' . $user->id . '&token=' . $token;

        $user->gender();
        $user->user_estates();
        $this->user = $user;
        $this->estate_types = UserEstateType::TYPES;
        $this->confirmation_url = env('APP_URL') . $route;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.admin.user-registered');
    }
}
