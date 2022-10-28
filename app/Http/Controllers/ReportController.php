<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Report;
use App\Models\Place;
use App\Models\Event;
use Inertia\Inertia;
use DB;

class ReportController extends Controller
{
    
    /**
     * list page
     */
    public function index()
    {
        $reportCollection = collect(DB::select(
            "SELECT reports.*, events.`date` ,places.location 
            FROM reports
            LEFT JOIN places ON places.id = reports.place_id
            LEFT JOIN events ON events.id = reports.event_id"));
        return Inertia::render('Reports/List', ['reports' => $reportCollection]);
    }
    /**
     * create page
     */
    public function create(Request $request)
    {
        if(isset($request->prev_event_id))
        {
            $prev_event_id = $request->prev_event_id;
        }
        else
        {
            $prev_event_id = "";
        }
        // dd($request->prev_event_id);
        $placeCollection = Place::all();
        $eventCollection = Event::all();
        return Inertia::render('Reports/Create', ['places' => $placeCollection, 'events' => $eventCollection, 
        'prev_event_id' => $prev_event_id
    ]);
    }

    /**
     * edit page
     */
    public function edit(int $id)
    {
        $placeCollection = Place::all();
        $eventCollection = Event::all();
        $report = Report::find($id);
        return Inertia::render('Reports/Edit', ['report' => $report, 'places' => $placeCollection, 'events' => $eventCollection]);
    }

    /**
     * destroy
     */
    public function destroy(int $id)
    {
        $report = Report::find($id);
        $report->delete();
        return Redirect::route('dashboard.reports');
    }

    /**
     * Store Report in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $report = new Report($request->validated());
        $report->save();
        return Redirect::route('dashboard.reports.create', ['prev_event_id' => $report->event_id]);
    }
    /**
     * Update Report in storage.
     */
    public function update(UpdateReportRequest $request, int $id)
    {
        $report = Report::find($id);
        $report->update($request->validated());
        return Redirect::route('dashboard.reports');
    }
}
