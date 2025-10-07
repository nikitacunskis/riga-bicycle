<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Report;
use App\Http\Requests\ReportRequest;

class WelcomepageController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Welcome');
    }

    public function dashboard(Request $request)
    {
        // --- Bikes overall (sum of all "countable" columns across all reports) ---
        $bikesOverall = 0;
        foreach (config('report.countables') as $field) {
            $bikesOverall += (int) Report::sum($field);
        }

        // --- Simple totals ---
        $reportsCount = Report::count();
        $eventsCount  = Event::count();

        // --- Events with report counts (ordered by date so we can compute trends) ---
        $eventsWithCounts = Event::withCount('reports')
            ->orderBy('date') // ensure chronological
            ->get(['id','date']); // keep it lean

        $maxReports = (int) $eventsWithCounts->max('reports_count');
        $topEvents  = $eventsWithCounts->where('reports_count', $maxReports)->values();

        // --- stats keyed by date for charts (date => reports_count) ---
        $eventsReportsCounts = $eventsWithCounts
            ->mapWithKeys(fn ($e) => [$e->date => (int) $e->reports_count])
            ->all();

        // --- Last vs previous ---
        $series = $eventsWithCounts->pluck('reports_count')->map(fn($v) => (int) $v)->values();
        $last = $series->last();
        $prev = $series->count() >= 2 ? $series->slice(-2, 1)->first() : null;

        $lastVsPrevious = [
            'status' => 'n/a',
            'delta'  => null,
        ];
        if (!is_null($prev)) {
            $delta = $last - $prev;
            $lastVsPrevious = [
                'status' => $delta > 0 ? 'up' : ($delta < 0 ? 'down' : 'flat'),
                'delta'  => $delta,
            ];
        }

        $streak = null;
        if ($series->count() >= 2) {
            $changes = [];
            for ($i = 1; $i < $series->count(); $i++) {
                $changes[$i] = $series[$i] <=> $series[$i - 1]; // -1, 0, 1
            }

            $lastChange = end($changes); // most recent sign
            if ($lastChange !== 0) {
                $dir = $lastChange > 0 ? 'positive' : 'negative';
                $len = 1; // at least the last step
                // extend streak backwards while sign matches and not flat
                for ($i = count($changes) - 2; $i >= 1; $i--) {
                    if ($changes[$i] === $lastChange) {
                        $len++;
                    } else {
                        break;
                    }
                }
                $streak = ['direction' => $dir, 'length' => $len];
            } else {
                $streak = null;
            }
        }

        // Next event (your helper)
        $nextEvent = next_friday_closest_to_15th();

        return Inertia::render('Dashboard', [
            'kpi' => [
                'bikesOverall'  => $bikesOverall,
                'reports'       => $reportsCount,
                'events'        => $eventsCount,
                'topEvents'     => $topEvents,
                'maxReports'    => $maxReports,
            ],
            'next_event'   => $nextEvent,
            'reports_stat' => $eventsReportsCounts,
            'reports_trend' => [
                'last_vs_previous' => $lastVsPrevious,   // {status: 'up'|'down'|'flat'|'n/a', delta: int|null}
                'streak'           => $streak,           // {direction: 'positive'|'negative', length: int} | null
            ],
        ]);
    }


}
