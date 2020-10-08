<?php


namespace App\Interfaces;

use App\Models\Gender;
use App\Models\UserEstateType;

interface iDirectory
{
    public const DIRECTORIES = [
        'user_estate_types' => UserEstateType::class,
        'genders' => Gender::class
    ];
}
