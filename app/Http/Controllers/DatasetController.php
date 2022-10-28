<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Event;
use App\Models\Place;
use Inertia\Inertia;

class DatasetController extends Controller 
{
    public function generateAvarageOverall()
    {
        /**
        *
        // {
        //     label: '2019',
        //     data: [55,33,56,23,21,1,44,2,12,62,19,11],
        // },
        // {
        //     label: '2018',
        //     data: [49,21,55,64,83,21,99,1,66,19,41,21],
        // }
         */
        $eventCollection = Event::all();
        $events = $eventCollection->toArray();

        $dataset = [];
        $countable = ['womens', 'man', 'children_self', 'children_passanger'];
        $month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        foreach($events as $event)
        {
            $date = explode('-', $event['date']);
            $date = [
                'year' => $date[0],
                'month' => $date[1],
                'day' => $date[2],
            ];
            $reports = Report::where('event_id', $event['id'])->get()->toArray();
            $sum = 0;
            foreach($reports as $report)
            {
                foreach($countable as $key)
                {
                    $sum += $report[$key];
                }
            }
            $avarage = $sum/(sizeof($reports) - 1);
            $dataset[$date['year']][$month[(int)$date['month']-1]] = $avarage;
        }

        /**
         * 
        {
            label: '2019',
            data: [55,33,56,23,21,1,44,2,12,62,19,11],
        },
         */
        $returnDataset = [];
        foreach($dataset as $year => $d)
        {
            $returnDataset[] = [
                'label' => $year,
                'data' => [],
            ];
            $key = sizeof($returnDataset) - 1;
            foreach($d as $monthData)
            {
                $returnDataset[$key]['data'][] = $monthData;
            }
        }
            
        return Inertia::render('Welcome', [
            // 'canLogin' => Route::has('login'),
            // 'canRegister' => Route::has('register'),
            // 'laravelVersion' => Application::VERSION,
            // 'phpVersion' => PHP_VERSION,
            'dataset' => $returnDataset,
        ]);
    }
}