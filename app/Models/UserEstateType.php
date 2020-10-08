<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEstateType extends Model
{
    use HasFactory;

    public const TYPES = [
        1 => 'Участок',
        2 => 'Квартира'
    ];

    public const SHORT_TYPES = [
        1 => 'уч.',
        2 => 'кв.'
    ];

    public $timestamps = false;


}
