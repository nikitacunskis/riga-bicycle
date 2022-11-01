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
            $reports = Report::where('event_id', $event['id'])
            ->whereIn('place_id', $filters['places'])
            ->get()
            ->toArray();

            switch($filters['method'])
            {
                case 'average':
                    $value = $this->avarage($reports, $filters);
                    break;
                case 'sum':
                    $value = $this->sum($reports, $filters);
                    break;
                case 'prc':
                    $value = $this->prc($reports, $filters);
                    break;
                default:
                    $value = $this->avarage($reports, $filters);
                    break;
            }

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
            'attributes' => [],
            'places' => [],
            'method' => '',
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

            if(preg_match("/place_(\d*)/", $e, $match))
            {
                $filters['places'][] = $match[1];
            }

            if(in_array($e, ['average', 'sum', 'prc']))
            {
                $filters['method'] = $e;
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

        $avg = 0;
        if(sizeof($reports) !== 0)
        {
            $avg = $sum/sizeof($reports);
        }
        return $avg;
    }

    private function sum($reports, $filters)
    {
        $sum = 0;
        foreach($reports as $report)
        {
            foreach($filters['objects'] as $key)
            {
                $sum += $report[$key];
            }
        }

        return $sum;
    }

    private function prc($reports, $filters)
    {
        $prcOverall = 0;
        foreach($reports as $report)
        {
            $fullSum = $report['womens'] + $report['man'] + $report['children_self'] + $report['children_passanger'];

            $selectedSum = 0;
            foreach($filters['objects'] as $key)
            {
                $selectedSum += $report[$key];
            }
            if($fullSum !== 0 && $selectedSum !== 0)
            {
                $prcOverall += $selectedSum/$fullSum * 100;
            }
        }
        $prcOverall = $prcOverall/sizeof($reports);

        return $prcOverall;
    }
}