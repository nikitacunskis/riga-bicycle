<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    protected Collection $reports;
    protected ?string $group;

    public function __construct(Collection $reports, ?string $group = null)
    {
        $this->reports = $reports;   // collection of Report models (eager-loaded with place, event)
        $this->group   = $group;     // 'event' | 'place' | null (only affects sheet title)
    }

    public function title(): string
    {
        return match ($this->group) {
            'event' => 'Reports by Event',
            'place' => 'Reports by Place',
            default => 'Reports',
        };
    }

    public function headings(): array
    {
        return [
            'id','womens','man','radway','pavement','biekpath','child_chairs','supermobility',
            'to_center','from_center','children_self','children_passanger',
            'created_at','updated_at',
            'place_id','place_location','place_coordinates',
            'event_id','event_date','event_weather',
        ];
    }

    public function collection(): Collection
    {
        return $this->reports;
    }

    public function map($r): array
    {
        return [
            $r->id, $r->womens, $r->man, $r->radway, $r->pavement, $r->biekpath, $r->child_chairs, $r->supermobility,
            $r->to_center, $r->from_center, $r->children_self, $r->children_passanger,
            optional($r->created_at)?->toDateTimeString(), optional($r->updated_at)?->toDateTimeString(),
            optional($r->place)?->id, optional($r->place)?->location, optional($r->place)?->coordinates,
            optional($r->event)?->id, optional($r->event)?->date, optional($r->event)?->weather,
        ];
    }
}
