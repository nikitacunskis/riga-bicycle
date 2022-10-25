export default class ReportSetup{
    items = [
        {
            label: "Place",
            id: 'place_id',
            foreign: 'location',
            type: 'hidden',
            visible: true,
            crud: true,
        },
        {
            label: "Event",
            id: 'event_id',
            foreign: 'date',
            type: 'hidden',
            visible: true,
            crud: true,
        },
        {
            label: "Womens",
            id: 'womens',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "Man",
            id: 'man',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "Roadway",
            id: 'radway',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "Pavement",
            id: 'pavement',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "Biekpath",
            id: 'biekpath',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "Child chairs",
            id: 'child_chairs',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "Supermobility",
            id: 'supermobility',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "To Center",
            id: 'to_center',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "From Center",
            id: 'from_center',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "Children Self",
            id: 'children_self',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        {
            label: "Children Passanger",
            id: 'children_passanger',
            type: 'number',
            foreign: false,
            visible: true,
            crud: true,
        },
        
    ];

    get getItemsShow(){
        return this.items;
    }
}