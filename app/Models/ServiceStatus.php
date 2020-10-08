<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['status_name'];

    public const STATUSES = [
        'active' => 1,
        'inactive' => 2
    ];
}
