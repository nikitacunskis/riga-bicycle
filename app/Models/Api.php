<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasFactory;

    protected $table = 'apis';

    protected $fillable = [
        'type',
        'key',
        'owner',
        'phone',
        'email',
        'purpose_of_use',
        'org_contact',
        'tos_accepted',
        'privacy_accepted',
        'reg_number',
        'valid_until',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
    ];
}
