<script setup>
import LineChart from '../../Components/Graphs/LineChart.vue';
import FrontLayout from '../../Layouts/FrontLayout.vue';
import RawTable from '../../Components/RawTable.vue';

const props = defineProps({
    dataset: {
        type: Object,
        default: {}
    },
    report: {
        type: Object,
        default: {}
    },
    raw: {
        type: Object,
        default: {}
    },
    e: {
        type: Array,
        default: []
    }
});


const randomColorPalette = () => {
    let colorPalette = ["#086788","#07a0c3","#f0c808","#fff1d0","#dd1c1a"];
    var palette = [];
    while(true){
        var r = Math.floor(Math.random() * 5);
        if(palette.indexOf(colorPalette[r]) === -1)
        {
            palette.push(colorPalette[r]);
        }
        if(palette.length >= colorPalette.length)
        {
            break;
        }
    }
    return palette;
}

const generateDataset = () => {
    let colorPalette = randomColorPalette();
    let generatedDataset = [];
    props.dataset.forEach( (e, index) => {
        generatedDataset.push({
            ...e,
            tension: 0.3,
            borderColor: colorPalette[index],
        })
    });
    return generatedDataset;
}

let chartData = {
    labels: ['Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnis', 'Jūlijs', 'Augusts', 'Septembirs', 'Oktobris', 'Novembris', 'Decembris'],
    datasets: generateDataset(),
}
let chartOptions = {
    responsive : true,
}

const breadcrumbs = [
    {
        text: 'Atskaites',
        href: '/report'
    },
];
</script>
<template>

<FrontLayout title="Atskaites" :breadcrumbs="breadcrumbs">

    <section class="overflow-x-auto bg-white p-1">
        <div class="sharethis-inline-share-buttons"></div>
        <p>Dati tiek ievākti katra mēneša 15. datumam tuvākajā piektdienā 8:00 - 9:00</p>
        <p>Redzamajā grafikā ir ievākti dati no sekojošiem punktiem:</p>
        <ul class="space-y-1 max-w-md list-disc list-inside">
            <li v-for="place in props.report.places">- {{place}}</li>
        </ul>
        <p>Grafikā ir apkopotas sekojošas velosipēdīstu grupas:</p>
        <ul class="space-y-1 max-w-md list-disc list-inside">
            <li v-for="object in props.report.objects">- {{object}}</li>
        </ul>
        <p>Apkopojot, tika izmantota metode "{{props.report.method}}"</p>
        <LineChart
            :chartData = "chartData"
            :chartOptions = "chartOptions"
        />
        <RawTable :reports="props.raw"/>
    </section>

</FrontLayout>
</template>
