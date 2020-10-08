<?php

namespace App\Models;

use App\Helpers\Encryptor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailReset extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_resets';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = ['user_id', 'new_email', 'token'];

    final public static function addToken(int $user_id, string $new_email): string {
        $token = Encryptor::getPasswordResetToken();

        self::updateOrCreate([ 'user_id' => $user_id ],
            [ 'new_email' => $new_email, 'token' => $token, 'created_at' => Carbon::now() ]);

        return $token;
    }

    final public static function reset(int $user_id, string $token): bool {
        $email_reset = self::where([
            ['user_id', $user_id],
            ['token', $token]
        ])->whereRaw('updated_at > date_sub(NOW(), interval 1 day)')->first();

        if (empty($email_reset)) {
            return false;
        }

        $user = User::find($user_id);

        if (empty($user)) {
            return false;
        }

        $user->setEmail($email_reset->new_email);
        $email_reset->delete();

        return true;
    }
}
