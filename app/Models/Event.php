<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable = [
        'date',
        'weather',
    ];

    public function weather()
    {
        return $this->belongsTo(Weather::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
