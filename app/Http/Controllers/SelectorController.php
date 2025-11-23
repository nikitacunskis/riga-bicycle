<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Report;
use App\Models\Place;
use App\Models\Api;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class SelectorController extends Controller
{
    public function index(
        Request $request
    )
    {
        $events = Event::all()->toArray();
        $years = [];
        foreach($events as $event)
        {
            $years[] = explode('-', $event['date'])[0];
        }
        $years = array_unique($years);
        $yearsOptions = [];
        foreach($years as $year)
        {
            $yearsOptions[] = [
                'id' => 'year_'.$year,
                'label' => $year,
            ];
        }
        $reportFields = (new Report())->getFillable();

        $form = [
            'options' => [
                'years' => [
                    'label' => 'Gadi',
                    'options' => $yearsOptions,
                ],
                'objects' => [
                    'label' => 'Objekti',
                    'options' => [
                        ['id' => 'womens', 'label' => 'Sievietes'],
                        ['id' => 'man', 'label' => 'Vīrieši'],
                        ['id' => 'children_self', 'label' => 'Bērni paši'],
                        ['id' => 'children_passanger', 'label' => 'Bērni kā pasažieri'],
                    ],
                ],
                'places' => [
                    'label' => 'Vietas',
                    'options' => [],
                ],
                'method' => [
                    'label' => 'Datu apkopošanas metode',
                    'options' => [
                        ['id' => 'average', 'label' => 'Vidējais'],
                        ['id' => 'sum', 'label' => 'Summa'],
                        ['id' => 'prc', 'label' => 'Procents no kopējā'],
                    ],
                ],
            ],
            'fields' => [],
        ];

        $fields = [];
        $places = Place::all()->toArray();
        foreach($places as $place)
        {
            $form['options']['places']['options'][] = [
                'id' => "place_" . $place['id'],
                'label' => $place['location'],
            ];
        }
        foreach($form['options'] as $field)
        {
            foreach($field['options'] as $option)
            {
                $fields[] = $option['id'];
            }
        }
        $form['fields'] = $fields;

        return Inertia::render('Selector/SelectorForm', ['fields' => $form]);
    }

    /**
     * GET /report-result
     * Renders selected report (dataset results)
     */
    public function result(Request $request): \Inertia\Response
    {
        $dataset = (new DatasetController())
            ->generateDataset(
                $request->input('selected', []),
                $request->input('method')
            );

        return Inertia::render('Selector/SelectedReport', [
            'dataset' => $dataset['dataset'],
            'report'  => $dataset['report'],
            'raw'     => $dataset['raw'],
        ]);
    }

    /**
     * API endpoint (unchanged)
     */
    public function apiReport(Request $request): JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $apiData = Api::where('key', $request->key)->first();
        if (! $apiData) {
            return response()->json(['error' => 'API key not valid'], 404);
        }

        $group = $request->query('group');
        if ($group && ! in_array($group, ['event', 'place'], true)) {
            return response()->json(['error' => 'Invalid group value. Use "event" or "place".'], 422);
        }

        $format = $request->query('format');
        if ($format && ! in_array($format, ['xlsx','csv'], true)) {
            return response()->json(['error' => 'Invalid format. Use "xlsx" or "csv".'], 422);
        }

        $reports = Report::with(['place','event'])
            ->get()
            ->makeHidden(['place_id','event_id']);

        // Grouping logic
        if ($group === 'event') {
            $payload = $reports->groupBy('event.id')->map(function ($items) {
                return [
                    'event'     => $items->first()->event,
                    'reports'   => $items->values(),
                    'by_place'  => $items->groupBy('place.id')->map(fn($g) => $g->values())->toArray(),
                ];
            });
        } elseif ($group === 'place') {
            $payload = $reports->groupBy('place.id')->map(function ($items) {
                return [
                    'place'     => $items->first()->place,
                    'reports'   => $items->values(),
                    'by_event'  => $items->groupBy('event.id')->map(fn($g) => $g->values())->toArray(),
                ];
            });
        } else {
            $payload = $reports;
        }

        // Spreadsheet download
        if ($format) {
            $export = new ReportsExport($reports, $group);
            $filename = 'reports'.($group ? "-{$group}" : '').'-'.now()->format('Ymd_His');

            return Excel::download(
                $export,
                $filename.'.'.$format,
                $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX,
                ['Content-Type' => $format === 'csv' ? 'text/csv' : null]
            );
        }

        // Default JSON response
        return response()->json([
            $group ? 'groupedReports' : 'reports' => $payload,
            'apiData' => $apiData,
            'meta' => [
                'group'  => $group ?? null,
                'count'  => $reports->count(),
                'format' => 'json',
            ],
        ]);
    }
}
