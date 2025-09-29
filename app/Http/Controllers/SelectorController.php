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
    /**
     * Renders Page with info about project variables to select for graph
     */
    public function index(
        Request $request
        /** #### CHANGED: accept Request so we can read flashed payload after redirect (PRG). */
    )
    {
        /** #### ADDED: if a payload was flashed by report(), render SelectedReport immediately. */
        if ($payload = $request->session()->pull('report_payload')) {
            return Inertia::render('Selector/SelectedReport', $payload);
        }

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
                        ['id' => 'children_passanger', 'label' => 'Bērni, kā pasažieri'],
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

    public function report(Request $request)
    {
        $dataset = (new DatasetController())->generateDataset($request->input('selected', []));

        $payload = [
            'dataset' => $dataset['dataset'],
            'report'  => $dataset['report'],
            'raw'     => $dataset['raw'],
        ];

        /** #### ADDED: 303 redirect to GET /report and flash the payload. */
        return to_route('page.report.get', [], 303)->with('report_payload', $payload);
    }

    public function apiReport(Request $request): JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $apiData = Api::where('key', $request->key)->first();
        if (! $apiData) {
            return response()->json(['error' => 'API key not valid'], 404);
        }

        $group = $request->query('group'); // 'event' | 'place' | null
        if ($group && ! in_array($group, ['event', 'place'], true)) {
            return response()->json(['error' => 'Invalid group value. Use "event" or "place".'], 422);
        }

        // New: format selector
        $format = $request->query('format'); // 'xlsx' | 'csv' | null
        if ($format && ! in_array($format, ['xlsx','csv'], true)) {
            return response()->json(['error' => 'Invalid format. Use "xlsx" or "csv".'], 422);
        }

        $reports = Report::with(['place','event'])
            ->get()
            ->makeHidden(['place_id','event_id']);

        // Build JSON payload (unchanged)
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

        // If Excel/CSV requested: return a file instead of JSON
        if ($format) {
            // Flatten to a single sheet; ignore deep grouping for spreadsheets.
            // If you really want grouping, create separate exports per group and a MultipleSheets export.
            $export = new ReportsExport($reports, $group);

            $filename = 'reports'.($group ? "-{$group}" : '').'-'.now()->format('Ymd_His');

            if ($format === 'xlsx') {
                return Excel::download($export, $filename.'.xlsx'); // Content-Disposition: attachment
            }

            // CSV path (Excel opens it fine)
            return Excel::download($export, $filename.'.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        // Default JSON (back-compatible)
        return response()->json([
            $group ? 'groupedReports' : 'reports' => $payload,
            'apiData' => $apiData,
            'meta' => [
                'group' => $group ?? null,
                'count' => $reports->count(),
                'format' => 'json',
            ],
        ]);
    }
}
