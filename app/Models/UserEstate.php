<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEstate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'estate_type_id', 'estate_number'
    ];

    public $timestamps = false;

    public static function setEstates($user_estates, $user_id) {
        $estates_ids = [];
        foreach ($user_estates as $estate_data) {
            if (!empty($estate_data['id'])) {
                self::findOrFail($estate_data['id'])
                    ->setEstate($estate_data);
                $estates_ids[] = $estate_data['id'];
            } else {
                $estates_ids[] = self::create($estate_data + [
                    'user_id' => $user_id
                ])->id;
            }
        }

        if (!empty($estates_ids)) {
            self::whereNotIn('id', $estates_ids)->delete();
        }
    }

    public function setEstate($user_estate) {
        $this->fill($user_estate);

        if ($this->isDirty()) {
            $this->save();
        }
    }
}
