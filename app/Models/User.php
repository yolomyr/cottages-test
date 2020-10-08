<?php

namespace App\Models;

use App\Casts\PhoneNumber;
use App\Mail\UserConfirmed;
use App\Traits\PasswordGenerator;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, PasswordGenerator;

    /**
     * Users random generated password length
     */
    public const PASSWORD_LENGTH = 16;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'birthday', 'gender_id', 'email', 'user_role_id', 'phone', 'whatsapp_phone', 'telegram_phone', 'viber_phone', 'work_info', 'hobby_info', 'family_info', 'extra_info', 'password', 'rating'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birthday' => 'date:Y-m-d',
        'phone' => PhoneNumber::class,
        'whatsapp_phone' => PhoneNumber::class,
        'telegram_phone' => PhoneNumber::class,
        'viber_phone' => PhoneNumber::class
    ];

    /**
     * User roles constant
     */
    public const ROLES = [
        'admin' => 1,
        'manager' => 2,
        'customer' => 3
    ];

    /**
     * Set users email method
     * @param string $new_email
     */
    final public function setEmail(string $new_email): void {
        $this->email = $new_email;
        $this->save();
    }

    /**
     * Set users password method
     * @param string $new_password
     */
    final public function setPassword(string $new_password): void {
        $this->password = Hash::make($new_password);
        $this->save();
    }

    final public function setVerified(): void {
        $this->user_verified_at = Carbon::now();
        $this->save();
    }

    /**
     * Set users role method
     * @param int $role_id
     */
    final public function setRole(int $role_id): void {
        $this->user_role_id = $role_id;
        $this->save();
    }

    /**
     * Set users email method
     * @param string $profile_data
     */
    final public function setProfile(array $profile_data): void {
        $this->fill($profile_data);

        if ($this->isDirty()) {
            $this->save();
        }

        if (!empty($profile_data['user_estates'])) {
            UserEstate::setEstates($profile_data['user_estates'], auth()->user()->id);
        }
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    final public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    final public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Get users full name
     * @return string
     */
    final public function getFullName(): string {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * Get user_estates records associated with the user.
     */
    public function user_estates()
    {
        return $this->hasMany(UserEstate::class);
    }

    /**
     * Get gender record associated with the user.
     */
    public function gender()
    {
        return $this->hasOne(Gender::class, 'id', 'gender_id');
    }

    /**
     * User create method
     * @param array $data
     * @param string $role
     * @return User
     */
    public static function createWithRole(array $data, string $role = 'customer'): User {
        $data['password'] = self::makePassword();
        $data['user_role_id'] = self::ROLES[$role];

        $user = self::create(
            $data
        );

        if (isset($data['user_estates']) && !empty($data['user_estates'])) {
            foreach ($data['user_estates'] as $estate) {
                UserEstate::create([
                    'user_id' => $user->id,
                    'estate_type_id' => $estate['estate_type_id'],
                    'estate_number' => $estate['estate_number']
                ]);
            }
        }

        return $user;
    }

    /**
     * Random password and based on it hash generate method
     * @param bool $get_only_hash
     * @return array|string
     */
    public static function makePassword(bool $get_only_hash = true) {
        $random_password = self::randomPassword(self::PASSWORD_LENGTH);
        $hash = Hash::make($random_password);

        return $get_only_hash ? $hash : [
            $random_password,
            Hash::make($random_password)
        ];
    }

    /**
     * Is user has admin permissions check method
     * @return bool
     */
    final public function isAdmin(): bool {
        return $this->user_role_id === self::ROLES['admin'];
    }

    final public static function getAdminEmails(): array {
        return self::where('user_role_id', self::ROLES['admin'])->get('email')->pluck('email')->toArray();
    }

    /**
     * User verification method. After verification sending access data to user email
     */
    final public function verify(): void {
        [$password, $hash] = self::makePassword(false);
        $this->password = $hash;
        $this->save();
        $this->setVerified();

        Mail::to($this->email)->send(new UserConfirmed($this, $password));
    }

    /**
     * Increase rating by 1
     */
    final public function increaseRating(): void {
        $this->rating++;
        $this->save();
    }

    /**
     * Decrease rating by 1 if rating is not 0
     */
    final public function decreaseRating(): void {
        if ($this->rating > 0) {
            $this->rating--;
            $this->save();
        }
    }
}
