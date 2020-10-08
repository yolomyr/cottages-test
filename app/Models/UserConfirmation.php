<?php

namespace App\Models;

use App\Helpers\Encryptor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConfirmation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_confirmations';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = ['user_id', 'token'];

    final public static function addToken(int $user_id): string {
        $token = Encryptor::getPasswordResetToken();

        self::create([
            'user_id' => $user_id,
            'token' => $token
        ]);

        return $token;
    }

    public static function confirm(int $user_id, string $token): bool {
        $user_confirmation = self::where([
            ['user_id', $user_id],
            ['token', $token]
        ])->whereRaw('updated_at > date_sub(NOW(), interval 30 day)')->first();

        if (empty($user_confirmation)) {
            return false;
        }

        $user = User::find($user_id);

        if (empty($user)) {
            return false;
        }

        $user->verify();
        $user_confirmation->delete();

        return true;
    }
}
