<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingType extends Model
{
    use HasFactory;

    public const TYPES = [
        'free' => 1,
        'family' => 2
    ];

    public const TYPES_NAMES = [
        1 => 'Свободная',
        2 => 'Семейный час'
    ];

    protected $fillable = ['booking_type_name'];

    public $timestamps = false;

}
