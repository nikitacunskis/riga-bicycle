<?php

namespace App\Http\Controllers;

use App\Support\Social\PostData;
use App\Support\Social\SocialPoster;
use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use App\Services\SocialCardGenerator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $eventCollection = Event::all();
        return Inertia::render('Events/List', ['events' => $eventCollection]);
    }

    public function create()
    {
        return Inertia::render('Events/Create');
    }

    public function edit(int $id)
    {
        $event = Event::find($id);
        return Inertia::render('Events/Edit', ['event' => $event]);
    }

    public function destroy(int $id)
    {
        $event = Event::find($id);
        $event->delete();
        return Redirect::route('dashboard.events.index');
    }

    public function store(Request $request)
    {
        $event = new Event($request->toArray());
        $event->save();
        return Redirect::route('dashboard.events.index');
    }

    public function update(Request $request, int $id)
    {
        $event = Event::find($id);
        $event->update($request->toArray());
        return Redirect::route('dashboard.events.index');
    }

    public function generate(Event $event, Request $request)
    {

        $previewUrl = route('events.image', ['event' => $event->id]);

        return Inertia::render('Events/Image', [
            'post_text' => $this->buildPostText($event),
            'image_url' => $previewUrl,
            'event_id'  => $event->id,
        ]);

    }

    public function image(Event $event, Request $request, SocialCardGenerator $cards)
    {
        $binary = $cards->stream([
            'title'         => Carbon::parse($event->date)->format('F Y'),
            'subtitle'      => $this->buildSubtitle($event),
            'logo'          => 'public/img/pilsēta-cilvēkiem-logo-horizontal-text-white.png',
            'watermark'     => 'veloskaitisana.pilsetacilvekiem.lv',
            'top_watermark' => $event->weather,
        ]);

        return Response::make($binary, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache',
        ]);
    }

    protected function buildPostText(Event $event): string
    {
        $title = Carbon::parse($event->date)->format('F Y');
        $report = $this->eventReportDataBuild($event);

        return "$title notika kārtējā ikmēneša veloskaitīšana. Šoreiz aktīvisti ir " .
            "saskaitījuši pavisam " . $report['countAll'] . " velobraucējus. " .
            "Datus var izpētīt projekta lapā https://veloskaitisana.pilsetacilvekiem.lv";
    }

    protected function buildSubtitle(Event $event): string
    {
        $report = $this->eventReportDataBuild($event);

        return
            "Vidēji pa visiem punktiem Rīgā pēc kategorijām\n" .
            "Vīrieši: {$report['total']['man']}\n" .
            "Sievietes: {$report['total']['womens']}\n" .
            "Bērni pasažieri: {$report['total']['children_passanger']}\n" .
            "Bērni paši: {$report['total']['children_self']}\n\n" .
            "Kopā tika saskaitīti {$report['countAll']} supermobilitātes transportrīki.\n\n" .
            "Lielākais punkts: {$report['biggestPlace']} [{$report['biggestTotal']}]\n" .
            "Mazākais punkts: {$report['smallestPlace']} [{$report['smallestTotal']}]";
    }

    protected function eventReportDataBuild(Event $event): array|string
    {
        $reports = Report::where('event_id', $event->id)->latest()->get();
        $reportCount = $reports->count();

        if ($reportCount === 0) {
            return "Nav datu par šo mēnesi.";
        }

        $total = [
            'man' => 0,
            'womens' => 0,
            'children_passanger' => 0,
            'children_self' => 0,
        ];

        $biggestPlace = null;
        $biggestTotal = 0;
        $smallestPlace = null;
        $smallestTotal = PHP_INT_MAX;
        $countAll = 0;

        foreach ($reports as $report) {
            $placeTotal = 0;

            foreach ($total as $k => $_) {
                $total[$k] += $report->{$k};
                $placeTotal += $report->{$k};
            }

            // <<< FIX HERE: only location text
            if ($placeTotal > $biggestTotal) {
                $biggestTotal = $placeTotal;
                $biggestPlace = $report->place->location;
            }

            if ($placeTotal < $smallestTotal) {
                $smallestTotal = $placeTotal;
                $smallestPlace = $report->place->location;
            }

            $countAll += $placeTotal;
        }

        foreach ($total as $key => $value) {
            $total[$key] = (int) round($total[$key] / $reportCount);
        }

        return [
            'total' => $total,
            'biggestPlace' => $biggestPlace,
            'biggestTotal' => $biggestTotal,
            'smallestPlace' => $smallestPlace,
            'smallestTotal' => $smallestTotal,
            'countAll' => $countAll,
        ];
    }

    public function shareAndPost(Event $event, Request $request, SocialPoster $poster)
    {
        $text = $this->buildPostText($event);

        if ($request->boolean('no-image')) {
            $postData = new PostData(
                text:  $text,
                link:  null,
                media: [],     // без картинок
                meta:  []
            );
            $res = $poster->post('x', $postData);

            return response()->json([
                'generated' => [
                    'text'  => $text,
                    'image' => null,
                ],
                'posted' => [
                    'ok'       => $res->ok,
                    'remoteId' => $res->remoteId ?? null,
                    'error'    => $res->error ?? null,
                    'raw'      => $res->raw,
                ],
            ]);
        }

        $publicUrl = route('events.image', ['event' => $event->id]);
        $postData = new PostData(
            text:  $text,
            link:  null,
            media: [$publicUrl],
            meta:  []
        );
        $res = $poster->post('x', $postData);

        return response()->json([
            'generated' => [
                'text'  => $text,
                'image' => $publicUrl,
            ],
            'posted' => [
                'ok'       => $res->ok,
                'remoteId' => $res->remoteId ?? null,
                'error'    => $res->error ?? null,
                'raw'      => $res->raw,
            ],
        ]);
    }

}
