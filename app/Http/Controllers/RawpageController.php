<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Place;
use App\Models\Report;
use App\Models\Weather;
use App\Http\Requests\ReportRequest;

class RawpageController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::all()->toArray();

        foreach($reports as $key => $report)
        {
            $reports[$key]['place'] = Place::where('id', $report['place_id'])->pluck('location')[0];
            $reports[$key]['event'] = Event::where('id', $report['event_id'])->pluck('date')[0];
            $reports[$key]['weather'] = Event::where('id', $report['event_id'])->pluck('weather')[0];
        }

        return Inertia::render('Raw', ['reports' => $reports]);
    }
}
