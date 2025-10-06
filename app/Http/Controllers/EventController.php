<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Services\SocialCardGenerator;
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

    public function generate(Event $event, Request $request, SocialCardGenerator $cards)
    {
        // Build the title from the event date: "October 2025"
        $title = Carbon::parse($event->date)->format('F Y');

        // Fetch related reports for this event (what you want to inspect now)
        $reports = Report::where('event_id', $event->id)->latest()->get();
        $reportCount = $reports->count();

        $total = [
            'man' => 0,
            'womens' => 0,
            'children_passanger' => 0,
            'children_self' => 0,
        ];
        $biggestPlace = null;
        $biggestPlaceResult = 0;
        $smallestPlace = $reports[0]->place;
        $smallestPlaceResult = 999999999;
        $countAll = 0;

        foreach ($reports as $key => $report) {
            $placeTotal = 0;
            foreach (array_keys($total) as $key) {
                $total[$key] += $report->{$key};
                $placeTotal += $report->{$key};
            }
            if ($biggestPlaceResult < $placeTotal) {
                $biggestPlace = $report->place;
                $biggestPlaceResult = $placeTotal;
            }
            if ($smallestPlaceResult > $placeTotal) {
                $smallestPlace = $report->place;
                $smallestPlaceResult = $placeTotal;
            }
            $countAll += $placeTotal;
        }

        foreach ($total as $key => $value) {
            $total[$key] = (int)($total[$key] / $reportCount);
        }

        // Show me the reports right now, as requested
//        dd([
//            'event'   => $event->only(['id','date']),
//            'title'   => $title,
//            'reports' => $reports,
//            'total'   => $total,
//        ]);

        $subtitle = "Vidēji pa visiem punktiem Rīgā pēc kategorijām\nVīrieši: {$total['man']}\nSievietes: {$total['womens']}\nBērni pasažieri: {$total['children_passanger']}\nBērni paši: {$total['children_self']}";
        $subtitle .= "\n\nKopā tika saskaitīti {$countAll} supermobilitātes transportrīki.";
        $subtitle .= "\n\nLielākais punkts: {$biggestPlace->location} [{$biggestPlaceResult}]\nMazākais punkt: {$smallestPlace->location} [{$smallestPlaceResult}]";
        // --- When you’re ready to generate the image, un-comment below ---

        $path = $cards->make([
            'title'         => $title,
            'subtitle'      => $request->input('subtitle', $subtitle),
            //             'bg'        => $request->input('bg', 'public/img/strudel_solinsh.jpg'),
            'logo'          => $request->input('logo', 'public/img/pilsēta-cilvēkiem-logo-horizontal-text-white.png'),
            'watermark'     => $request->input('watermark', 'veloskaitisana.pilsetacilvekiem.lv'),
            'top_watermark' => $event->weather,
        ]);

        $relative = str_replace(Storage::disk('public')->path(''), '', $path);
        $url = Storage::disk('public')->url($relative);

        return Inertia::render('Events/Image', ['image' => $url]);
    }
}
