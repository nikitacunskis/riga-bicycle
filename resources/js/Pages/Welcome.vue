
<script setup>
    import LineChart from '@/Components/Graphs/LineChart.vue';

    const props = defineProps({
        dataset: Object,
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
    let data = [
        {
            label: '2019',
            data: [55,33,56,23,21,1,44,2,12,62,19,11],
        },
        {
            label: '2018',
            data: [49,21,55,64,83,21,99,1,66,19,41,21],
        }
    ];
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
        labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
        datasets: generateDataset(),
    }
    let chartOptions = {
        responsive : true,
    }

    console.log(chartData);
</script>
<template>

    <LineChart 
        :chartData = "chartData"
        :chartOptions = "chartOptions"
    />

</template>