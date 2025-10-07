<?php

namespace App\Services;

use App\Models\Place;

class  PlaceService
{
    public static function getEventsInPlaces() {
        $places = Place::all();
        $report = [];
    }
}
