<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $fillable = [
        'place_id',
        'event_id',
        'womens',
        'man',
        'radway',
        'pavement',
        'biekpath',
        'child_chairs',
        'supermobility',
        'to_center',
        'from_center',
        'children_self',
        'children_passanger',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
