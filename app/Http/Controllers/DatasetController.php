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

        $events  = Event::all()->toArray();
        $this->filterEvents($events, $filters);

        $dataset = [];
        $month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $raw = [];
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

            $raw = array_merge($raw, $reports);

            $dataset[$date['year']][$month[(int)$date['month']-1]] = [
                'value' => $this->value($reports, $filters),
                'report' => $this->datasetReport($reports, $filters),
            ];
        }


        foreach($raw as $key => $rawReport)
        {
            $raw[$key]['place'] = Place::where('id', $rawReport['place_id'])->pluck('location')[0];
            $raw[$key]['event'] = Event::where('id', $rawReport['event_id'])->pluck('date')[0];
            $raw[$key]['weather'] = Event::where('id', $rawReport['event_id'])->pluck('weather')[0];
        }


        $returnDataset = [];
        $returnReport = [
            'places' => [],
            'objects' => [],
        ];
        foreach($dataset as $year => $d)
        {
            $returnDataset[] = [
                'label' => $year,
                'data' => [],
            ];
            $key = sizeof($returnDataset) - 1;
            foreach($d as $data)
            {
                $returnDataset[$key]['data'][] = $data['value'];
                $returnReport['places'] = array_merge($returnReport['places'], $data['report']['places']);
                $returnReport['objects'] = array_merge($returnReport['objects'], $data['report']['objects']);
                $returnReport['method'] = $data['report']['method'];
            }
        }
        $returnReport['places'] = array_unique($returnReport['places']);
        $returnReport['objects'] = array_unique($returnReport['objects']);
        return [
            'dataset' => $returnDataset,
            'report' => $returnReport,
            'raw' => $raw,
        ];
    }

    private function value($reports, $filters)
    {
        switch($filters['method'])
        {
            case 'average':
                return $this->avarage($reports, $filters);
            case 'sum':
                return $this->sum($reports, $filters);
            case 'prc':
                return $this->prc($reports, $filters);
            default:
                return $this->avarage($reports, $filters);
        }
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

    private function avarage(array $reports, array $filters)
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

    private function sum(array $reports, array $filters)
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

    private function prc(array $reports, array $filters)
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
        if(sizeof($reports) !== 0)
        {
            $prcOverall = $prcOverall/sizeof($reports);
        }

        return $prcOverall;
    }

    private function datasetReport(array $reports, array $filters) : array
    {
        $allPlaces = Place::all()->pluck('location')->toArray();
        $places = Place::whereIn('id', $filters['places'])->pluck('location')->toArray();

        if(sizeof($allPlaces) === sizeof($places))
        {
            $places = ['visi skaitīšanas punkti visā Rīga'];
        }
        if(sizeof($places) === 0)
        {
            $places = ['nav izvēlēts neviens punkts'];
        }

        $objects = [];
        $ids = ['womens', 'man', 'children_self', 'children_passanger'];
        $labels = ['Sievietes', 'Vīrieši', 'Bērni paši', 'Bērni, kā pasažieri'];
        if(sizeof($filters['objects']) === 0)
        {
            $objects = ['nav izvēlēta neviena pētījuma kategorija'];
        }
        else
        {
            foreach($filters['objects'] as $obj)
            {
                $objects[] = $labels[array_search($obj, $ids)];          
            }
        }

        $methods = ['average' => 'Vidējais', 'sum' => 'Summēšana', 'prc' => 'Procents no visiem'];
        if(!isset($filters['method']))
        {
            $method = $methods['average'];
        }
        else
        {
            $method = $methods[$filters['method']];
        }

        return [
            'places' => $places,
            'objects' => $objects,
            'method' => $method,
        ];
    }
}