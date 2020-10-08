<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBookingScheduleType extends Model
{
    use HasFactory;

    public const TYPES = [
        'availability' => 1,
        'limitation' => 2
    ];

    protected $fillable = ['schedule_type_name'];

    public $timestamps = false;
}
