<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Event;
use App\Models\Place;
use Inertia\Inertia;
use DB;

class DatasetController extends Controller
{
    /**
     * Build dataset for selected filters + selected method
     */
    public function generateDataset(array $selected, ?string $method = null): array
    {
        $filters = $this->filterArray($selected);

        // Override method if explicitly provided
        if ($method && in_array($method, ['average','sum','prc'], true)) {
            $filters['method'] = $method;
        }

        $events = Event::all()->toArray();
        $this->filterEvents($events, $filters);

        $dataset = [];
        $raw = [];
        $months = ["January","February","March","April","May","June","July","August","September","October","November","December"];

        foreach ($events as $event) {
            $date = explode('-', $event['date']);
            $date = [
                'year'  => $date[0],
                'month' => (int)$date[1],
            ];

            $reports = Report::where('event_id', $event['id'])
                ->whereIn('place_id', $filters['places'])
                ->get()
                ->toArray();

            $raw = array_merge($raw, $reports);

            $dataset[$date['year']][$months[$date['month'] - 1]] = [
                'value'  => $this->value($reports, $filters),
                'report' => $this->datasetReport($reports, $filters),
            ];
        }

        // Enrich RAW in one go (no N+1)
        $raw = $this->enrichRaw($raw);

        return $this->formatReturn($dataset, $raw);
    }

    /**
     * Dispatch method
     */
    private function value(array $reports, array $filters): float|int
    {
        return match ($filters['method']) {
            'sum' => $this->sum($reports, $filters),
            'prc' => $this->prc($reports, $filters),
            default => $this->average($reports, $filters),
        };
    }

    /**
     * Extract selected filters
     */
    private function filterArray(array $selected): array
    {
        $filters = [
            'years'     => [],
            'objects'   => [],
            'direction' => [],
            'roadType'  => [],
            'attributes'=> [],
            'places'    => [],
            'method'    => '',
        ];

        foreach ($selected as $e) {
            if (preg_match("/year_(\d+)/", $e, $match)) {
                $filters['years'][] = $match[1];
            }

            if (in_array($e, ['womens','man','children_self','children_passanger'], true)) {
                $filters['objects'][] = $e;
            }

            if (preg_match("/place_(\d+)/", $e, $match)) {
                $filters['places'][] = $match[1];
            }

            if (in_array($e, ['average','sum','prc'], true)) {
                $filters['method'] = $e;
            }
        }

        return $filters;
    }

    /**
     * Filter events list by years
     */
    private function filterEvents(array &$events, array $filters): void
    {
        foreach ($events as $key => $event) {
            if (!in_array(explode('-', $event['date'])[0], $filters['years'], true)) {
                unset($events[$key]);
            }
        }
    }

    /**
     * AVERAGE calculation
     */
    private function average(array $reports, array $filters): float|int
    {
        if (!$reports) {
            return 0;
        }

        $sum = 0;
        foreach ($reports as $report) {
            foreach ($filters['objects'] as $obj) {
                $sum += $report[$obj];
            }
        }

        return $sum / count($reports);
    }

    /**
     * SUM calculation
     */
    private function sum(array $reports, array $filters): float|int
    {
        $sum = 0;

        foreach ($reports as $report) {
            foreach ($filters['objects'] as $obj) {
                $sum += $report[$obj];
            }
        }

        return $sum;
    }

    /**
     * PERCENT calculation
     */
    private function prc(array $reports, array $filters): float|int
    {
        if (!$reports) {
            return 0;
        }

        $fullTotal = 0;
        $selectedTotal = 0;

        foreach ($reports as $report) {
            $fullTotal += $report['womens']
                + $report['man']
                + $report['children_self']
                + $report['children_passanger'];

            foreach ($filters['objects'] as $obj) {
                $selectedTotal += $report[$obj];
            }
        }

        if ($fullTotal === 0) {
            return 0;
        }

        return ($selectedTotal / $fullTotal) * 100;
    }


    /**
     * Build readable legend + labels
     */
    private function datasetReport(array $reports, array $filters): array
    {
        $allPlaces = Place::all()->pluck('location')->toArray();
        $places = Place::whereIn('id', $filters['places'])->pluck('location')->toArray();

        if (count($allPlaces) === count($places)) {
            $places = ['visi skaitīšanas punkti visā Rīga'];
        }
        if (!$places) {
            $places = ['nav izvēlēts neviens punkts'];
        }

        $ids = ['womens','man','children_self','children_passanger'];
        $labels = ['Sievietes','Vīrieši','Bērni paši','Bērni, kā pasažieri'];

        if (!$filters['objects']) {
            $objects = ['nav izvēlēta neviena pētījuma kategorija'];
        } else {
            $objects = [];
            foreach ($filters['objects'] as $obj) {
                $objects[] = $labels[array_search($obj, $ids)];
            }
        }

        $methods = ['average'=>'Vidējais','sum'=>'Summēšana','prc'=>'Procents no visiem'];
        $method = $filters['method'] ? $methods[$filters['method']] : $methods['average'];

        return compact('places', 'objects', 'method');
    }

    /**
     * Hydrate RAW once, no N+1
     */
    private function enrichRaw(array $raw): array
    {
        $placeNames = Place::pluck('location','id')->all();
        $events = Event::pluck('date','id')->all();
        $weather = Event::pluck('weather','id')->all();

        foreach ($raw as $k => $r) {
            $raw[$k]['place']   = $placeNames[$r['place_id']] ?? null;
            $raw[$k]['event']   = $events[$r['event_id']] ?? null;
            $raw[$k]['weather'] = $weather[$r['event_id']] ?? null;
        }

        return $raw;
    }

    /**
     * Final shape for chart + report info
     */
    private function formatReturn(array $dataset, array $raw): array
    {
        $finalDataset = [];
        $report = [
            'places'  => [],
            'objects' => [],
            'method'  => null,
        ];

        foreach ($dataset as $year => $months) {
            $finalDataset[] = ['label'=>$year, 'data'=>[]];
            $idx = array_key_last($finalDataset);

            foreach ($months as $m) {
                $finalDataset[$idx]['data'][] = $m['value'];
                $report['places']  = array_merge($report['places'], $m['report']['places']);
                $report['objects'] = array_merge($report['objects'], $m['report']['objects']);
                $report['method']  = $m['report']['method'];
            }
        }

        $report['places']  = array_unique($report['places']);
        $report['objects'] = array_unique($report['objects']);

        return [
            'dataset' => $finalDataset,
            'report'  => $report,
            'raw'     => $raw,
        ];
    }

    /**
     * Full dataset recalculation for ALL entries (unchanged logic)
     */
    public function generateAllDataset(string $method = 'average'): array
    {
        $allowed = ['average','sum','prc'];
        if (!in_array($method, $allowed, true)) {
            $method = 'average';
        }

        $allYears = Event::query()
            ->select(DB::raw("DISTINCT SUBSTRING_INDEX(`date`, '-', 1) AS y"))
            ->pluck('y')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $allPlaceIds = Place::query()->pluck('id')->all();
        $allObjects = ['womens','man','children_self','children_passanger'];

        $selected = array_merge(
            array_map(fn($y) => "year_{$y}", $allYears),
            array_map(fn($pid) => "place_{$pid}", $allPlaceIds),
            $allObjects,
            [$method]
        );

        return $this->generateDataset($selected, $method);
    }
}
