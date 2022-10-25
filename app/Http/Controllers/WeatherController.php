<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Weather;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        $weather = Weather::all()->where('date', $request->date);
        return json_encode($weather);
    }
}
