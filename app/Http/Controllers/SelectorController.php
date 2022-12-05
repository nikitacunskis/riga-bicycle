<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Report;
use App\Models\Place;
use App\Http\Requests\ReportRequest;

class SelectorController extends Controller
{
    /**
     * Renders Page with info about project variables to select for graph
     */
    public function index()
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
                        [
                            'id' => 'womens',
                            'label' => 'Sievietes',
                        ], 
                        [
                            'id' => 'man', 
                            'label' => 'Vīrieši',
                        ], 
                        [
                            'id' => 'children_self', 
                            'label' => 'Bērni paši',
                        ], 
                        [
                            'id' => 'children_passanger',
                            'label' => 'Bērni, kā pasažieri',
                        ], 
                    ],
                ],
                // 'direction' => [
                //     'label' => 'Virzieni',
                //     'options' => [
                //         [
                //             'id' => 'to_center', 
                //             'label' => 'Uz centru',
                //         ], 
                //         [
                //             'id' => 'from_center',
                //             'label' => 'No centra',
                //         ], 
                //     ],
                // ],
                // 'roadType' => [
                //     'label' => "Ceļa tips",
                //     'options' => [
                //         [
                //             'id' => 'radway', 
                //             'label' => 'Brauktuve',
                //         ], 
                //         [
                //             'id' => 'pavement', 
                //             'label' => 'Ietve',
                //         ], 
                //         [
                //             'id' => 'biekpath',
                //             'label' => 'Velojosla/ veloceļš',
                //         ],
                //     ],
                // ],
                // 'attributes' => [
                //     'label' => 'Cits',
                //     'options' => [
                //         [
                //             'id' => 'child_chairs',
                //             'label' => 'Bērnu krēsls',
                //         ], 
                //         [ 
                //             'id' => 'supermobility',
                //             'label' => 'Supermobilitāte ',
                //         ], 
                //     ],
                // ],
                'places' => [
                    'label' => 'Vietas',
                    'options' => [],
                ],
                'method' => [
                    'label' => 'Datu apkopošanas metode',
                    'options' => [
                        [
                            'id' => 'average',
                            'label' => 'Vidējais',
                        ],
                        [
                            'id' => 'sum',
                            'label' => 'Summa',
                        ],
                        [
                            'id' => 'prc',
                            'label' => 'Procents no kopējā',
                        ],
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
        foreach($form['options'] as $key => $field)
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
        $dataset = (new DatasetController())->generateDataset($request->selected);
        return Inertia::render('Selector/SelectedReport', [
            'dataset' => $dataset['dataset'], 
            // 'interpolation' => $dataset['interpolation'], 
            'report' => $dataset['report'], 
            'raw' => $dataset['raw']
        ]);
    }
}
