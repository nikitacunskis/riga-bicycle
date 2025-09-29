<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasFactory;

    protected $table = 'apis';

    protected $fillable = [
        'key',
        'owner',
        'valid_until',
        'phone',
        'email',
        'tos_accepted',
        'privacy_accepted',
        'type',          // enum: personal | organisation
        'reg_number',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
    ];
}
