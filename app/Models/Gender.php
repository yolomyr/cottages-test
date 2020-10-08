<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Array_;

class Gender extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender'
    ];

    public const GENDERS = [
        1 => 'Мужской',
        2 => 'Женский'
    ];

    public $timestamps = false;
}
