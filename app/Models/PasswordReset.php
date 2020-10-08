<?php

namespace App\Models;

use App\Helpers\Encryptor;
use App\Mail\UserPasswordRestore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class PasswordReset extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'email';
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['email', 'token', 'created_at'];

    public static function notify(string $user_email): bool {
        $user = User::select([ 'id', 'email', 'name', 'surname' ])->where('email', $user_email)->first();

        if (empty($user)) {
            return false;
        }

        $token = Encryptor::getPasswordResetToken($user_email);

        $password_reset = self::updateOrCreate([ 'email' => $user_email ],
            [ 'token' => $token, 'created_at' => Carbon::now() ]);

        if (empty($password_reset)) {
            return false;
        }

        Mail::to($user_email)->send(new UserPasswordRestore($user, $token));

        return true;
    }
}
