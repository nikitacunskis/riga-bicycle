<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\Redirect;
use App\Models\Event;
use Inertia\Inertia;
use DB;

class EventController extends Controller
{
    
    /**
     * list page
     */
    public function index()
    {
        $eventCollection = Event::all();
        return Inertia::render('Events/List', ['events' => $eventCollection]);
    }
    /**
     * create page
     */
    public function create()
    {
        return Inertia::render('Events/Create');
    }

    /**
     * edit page
     */
    public function edit(int $id)
    {
        $event = Event::find($id);
        return Inertia::render('Events/Edit', ['event' => $event]);
    }

    /**
     * destroy
     */
    public function destroy(int $id)
    {
        $event = Event::find($id);
        $event->delete();
        return Redirect::route('dashboard.events');
    }

    /**
     * Store Event in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $event = new Event($request->validated());
        $event->save();
        return Redirect::route('dashboard.events');
    }
    /**
     * Update Event in storage.
     */
    public function update(UpdateEventRequest $request, int $id)
    {
        $event = Event::find($id);
        $event->update($request->validated());
        return Redirect::route('dashboard.events');
    }

    public function getWeather(string $date)
    {
        $weather = Weather::find($date);
        return $weather;
    }
}
