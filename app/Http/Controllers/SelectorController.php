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
                    'label' => 'Years',
                    'options' => $yearsOptions,
                ],
                'objects' => [
                    'label' => 'Objects',
                    'options' => [
                        [
                            'id' => 'womens',
                            'label' => 'Womans',
                        ], 
                        [
                            'id' => 'man', 
                            'label' => 'Man',
                        ], 
                        [
                            'id' => 'children_self', 
                            'label' => 'Child self',
                        ], 
                        [
                            'id' => 'children_passanger',
                            'label' => 'Child as passanger',
                        ], 
                    ],
                ],
                'direction' => [
                    'label' => 'Directions',
                    'options' => [
                        [
                            'id' => 'to_center', 
                            'label' => 'To center',
                        ], 
                        [
                            'id' => 'from_center',
                            'label' => 'From center',
                        ], 
                    ],
                ],
                'roadType' => [
                    'label' => "Road Type",
                    'options' => [
                        [
                            'id' => 'radway', 
                            'label' => 'Roadway',
                        ], 
                        [
                            'id' => 'pavement', 
                            'label' => 'Pavement',
                        ], 
                        [
                            'id' => 'biekpath',
                            'label' => 'Bikepath',
                        ],
                    ],
                ],
                'attributes' => [
                    'label' => 'Attributes',
                    'options' => [
                        [
                            'id' => 'child_chairs',
                            'label' => 'Child chairs',
                        ], 
                        [ 
                            'id' => 'supermobility',
                            'label' => 'Supermobility',
                        ], 
                    ],
                ],
                'places' => [
                    'label' => 'Places',
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
        return Inertia::render('Selector/SelectedReport', ['dataset' => $dataset['dataset'], 'report' => $dataset['report']]);
    }
}
