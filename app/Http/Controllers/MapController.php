<?php
// app/Http/Controllers/MapController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function events()
    {
        return Event::orderBy('date')->get(['id','date']);
    }

    public function places(Request $req)
    {
        $eventId = $req->integer('event_id'); // optional filter

        // sum of your countable fields
        $cols = config('report.countables', []); // e.g. ['womens','man','radway',...]
        $sumExpr = $cols
            ? collect($cols)->map(fn($c) => "COALESCE(SUM(r.$c),0)")->implode(' + ')
            : '0';

        $q = Place::query()
            ->leftJoin('reports as r','r.place_id','=','places.id')
            ->when($eventId, fn($q)=>$q->where('r.event_id',$eventId))
            ->groupBy('places.id','places.location','places.lat','places.lng')
            ->selectRaw('
              places.id, places.location, places.lat, places.lng,
              COUNT(r.id) as reports_total,
              COUNT(DISTINCT r.event_id) as events_total,
              '.$sumExpr.' as bikes_total
            ');

        $rows = $q->get();

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $rows->map(function($p){
                return [
                    'type' => 'Feature',
                    'geometry' => ['type'=>'Point','coordinates'=>[(float)$p->lng,(float)$p->lat]],
                    'properties' => [
                        'id' => (int)$p->id,
                        'name' => $p->location,
                        'reports_total' => (int)$p->reports_total,
                        'events_total'  => (int)$p->events_total,
                        'bikes_total'   => (int)$p->bikes_total,
                    ],
                ];
            })->values(),
        ]);
    }
}
