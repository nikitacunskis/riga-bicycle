<script setup>

const props = defineProps({
    reports: Object,
});

function showWeather(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById('button_'+id).classList.add('hidden');
}
function showAllWeather()
{
    let spans = document.getElementsByClassName('hidden_weather');
    let buttons = document.getElementsByClassName('showhide_button');

    for(let span of spans)
    {
        span.classList.remove('hidden');
    }
    for(let button of buttons)
    {
        button.classList.add('hidden');
    }
    
}
</script>
<template>
    <div class="overflow-x-auto relative">
            <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                <thead class="text-[8px] text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-0.5">
                            Datums
                        </th>
                        <th scope="col" class="p-0.5">
                            Laika apstākļi<br>
                            <button type="button" @click="showAllWeather()">Rādīt visu</button>
                        </th>
                        <th scope="col" class="p-0.5">
                            Vieta
                        </th>
                        <th scope="col" class="p-0.5">
                            Sievietes
                        </th>
                        <th scope="col" class="p-0.5">
                            Vīrieši
                        </th>
                        <th scope="col" class="p-0.5">
                            Bērni (paši)
                        </th>
                        <th scope="col" class="p-0.5">
                            Bērni (pasažieri)
                        </th>
                        <th scope="col" class="p-0.5">
                            Ceļš
                        </th>
                        <th scope="col" class="p-0.5">
                            Ietve
                        </th>
                        <th scope="col" class="p-0.5">
                            Veloceļš/josla
                        </th>
                        <th scope="col" class="p-0.5">
                            Uz Centru
                        </th>
                        <th scope="col" class="p-0.5">
                            No Centra
                        </th>
                        <th scope="col" class="p-0.5">
                            Bērnu krēsli
                        </th>
                        <th scope="col" class="p-0.5">
                            Supermobilitāte
                        </th>
                    </tr>
                </thead>
                <tbody class="text-[10px]">
                    <tr class="border-b dark:bg-gray-800 dark:border-gray-700 first:bg-white last:bg-gray-50 hover:bg-gray-50" v-for="report in props.reports">
                        <th scope="col" class="p-0.5">
                            {{report.event}}
                        </th>
                        <th scope="col" class="p-0.5">
                            <button type="button" @click="showWeather(report.event+'_'+report.place_id)" :id="'button_'+report.event+'_'+report.place_id" class="showhide_button">Rādīt</button>
                            <span class="hidden hidden_weather" :id="report.event+'_'+report.place_id">{{report.weather}}</span>
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.place}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.womens}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.man}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.children_self}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.children_passanger}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.radway}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.pavement}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.biekpath}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.to_center}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.from_center}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.child_chairs}}
                        </th>
                        <th scope="col" class="p-0.5">
                            {{report.supermobility}}
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>    
</template>