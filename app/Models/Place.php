<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = ['location','coordinates','lat','lng'];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function reports() { return $this->hasMany(Report::class); }
}
