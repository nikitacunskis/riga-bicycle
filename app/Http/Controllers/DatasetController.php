<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Event;
use App\Models\Place;
use Inertia\Inertia;
use DB;

class DatasetController extends Controller 
{
    public function generateDataset(array $selected)
    {   
        $filters = $this->filterArray($selected);

        $eventCollection = Event::all();
        $events = $eventCollection->toArray();
        $this->filterEvents($events, $filters);

        $dataset = [];
        $month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        foreach($events as $event)
        {
            $date = explode('-', $event['date']);
            $date = [
                'year' => $date[0],
                'month' => $date[1],
            ];
            $reports = Report::where('event_id', $event['id'])->get()->toArray();

            $value = $this->avarage($reports, $filters);

            $dataset[$date['year']][$month[(int)$date['month']-1]] = $value;
        }

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
        return $returnDataset;
    }

    private function filterArray(array $selected)
    {
        $filters = [
            'years' => [],
            'objects' => [],
            'direction' => [],
            'roadType' => [],
            'attributes' => []
        ];

        foreach($selected as $k => $e)
        {
            if(preg_match("/year_(\d*)/", $e, $match))
            {
                $filters['years'][] = $match[1];
            }

            if(in_array($e,['womens','man','children_self','children_passanger']))
            {
                $filters['objects'][] = $e;
            }

            if(in_array($e, ['to_center', 'from_center']))
            {
                $filters['direction'][] = $e;
            }

            if(in_array($e, ['radway', 'pavement', 'biekpath']))
            {
                $filters['roadType'][] = $e;
            }

            if(in_array($e, ['child_chairs', 'supermobility']))
            {
                $filters['attributes'][] = $e;
            }
        }

        return $filters;
    }

    private function filterEvents(&$events, array $filters)
    {
        foreach($events as $key => $event)
        {
            if(!in_array(explode('-',$event['date'])[0], $filters['years']))
            {
                unset($events[$key]);
            }
        }
    }

    private function avarage($reports, $filters)
    {
        $sum = 0;
        foreach($reports as $report)
        {
            foreach($filters['objects'] as $key)
            {
                $sum += $report[$key];
            }
        }
        return $sum/(sizeof($reports) - 1);
    }
}