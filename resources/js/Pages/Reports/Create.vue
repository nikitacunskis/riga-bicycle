<script setup>
/*** DO NOT REMOVE OR REFACTOR, ONLY EXTENDED FIXED VERSION ***/
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from "vue";
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ReportSetup from '@/Components/Reports/ReportSetup.js';
import InputField from '@/Components/Reports/InputField.vue';
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    places: Object,
    events: Object,
    prev_event_id: { type: String, default: '' }
});

let reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow;

/*** FORM INIT ***/
const form = useForm({
    place_id: '', event_id: props.prev_event_id,
    womens: '', man: '', radway: '', pavement: '', biekpath: '',
    child_chairs: '', supermobility: '', to_center: '', from_center: '',
    children_self: '', children_passanger: '',
});

/*** ======== Mapping Fixes ======== ***/
const mappingMode = ref('v2');
const showCustom = ref(false);
const customMap = ref({});

const presets = {
    v1: { place_id:0,womens:1,man:2,radway:5,pavement:6,biekpath:7,child_chairs:8,supermobility:9,to_center:10,from_center:11,children_self:12,children_passanger:13 },
    v2: { place_id:1,womens:2,man:3,radway:5,pavement:6,biekpath:7,child_chairs:9,supermobility:10,to_center:11,from_center:12,children_self:13,children_passanger:14 }
};

// convert letter like "A" to index
const letterToIndex = (l) => {
    if (!l) return 0;
    l = String(l).trim().toUpperCase();
    return /^[A-Z]+$/.test(l) ? l.charCodeAt(0) - 65 : parseInt(l);
};

// load saved or assign letter presets
onMounted(() => {
    let saved = localStorage.getItem('excelCustomMap');
    if (saved) {
        customMap.value = JSON.parse(saved);
    } else {
        const letterPreset = {};
        Object.keys(presets.v2).forEach(key => {
            letterPreset[key] = String.fromCharCode(65 + presets.v2[key]);
        });
        customMap.value = letterPreset;
    }
});

// save custom mapping
const saveCustom = () => localStorage.setItem('excelCustomMap', JSON.stringify(customMap.value));

// close custom editor if mode != custom
watch(mappingMode, (val) => {
    if (val !== 'custom') showCustom.value = false;
});

/*** ===== Excel Import Logic ===== ***/
const isNumeric = (str) => !isNaN(str) && !isNaN(parseFloat(str));

const excelString = () => {
    let data = document.getElementById('excel-string').value.split('\t');

    console.log(data)

    let mode = String(mappingMode.value).trim();
    let activeMap = mode === 'custom'
        ? customMap.value
        : presets[mode] ?? presets.v2; // fallback to v2, never v1 by mistake

    Object.keys(activeMap).forEach(k => {
        let idx = activeMap[k];
        if (typeof idx === "string") idx = letterToIndex(idx);
        form[k] = isNumeric(data[idx]) ? data[idx] : 0;
    });

    fieldsToRender.forEach(f => {
        if (f.id !== 'event_id') {
            document.getElementById(f.id).value = isNumeric(form[f.id]) ? form[f.id] : 0;
        }
    });
};

/*** ===== Submit: unchanged ===== ***/
const submit = () => {
    fieldsToRender.forEach(e => form[e.id] = document.getElementById(e.id).value);
    form.place_id = document.getElementById('places').value;
    form.event_id = document.getElementById('events').value;
    form.post(route('dashboard.reports.store'));
};

/*** Fill report defaults ***/
let report = {};
fieldsToRender.forEach(e => report[e.id] = 0);
</script>

<template>
    <AdminLayout title="Dashboard - Create Report">
        <form @submit.prevent="submit">

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton>Create</PrimaryButton>
            </div>

            <div>
                <label for="places"
                       class="block mb-2 text-sm"
                >Place</label>
                <select id="places"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                    <option v-for="place in props.places" :value="place.id">{{ place.location }}</option>
                </select>
            </div>

            <div class="mt-4">
                <label for="events" class="block mb-2 text-sm">Events</label>
                <select id="events"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                    <option v-for="event in props.events" :value="event.id">{{ event.date }}</option>
                </select>
            </div>

            <section class="border m-5 p-5 text-white bg-gray-900 rounded">
                <InputLabel value="Import row from Excel table (copy/paste full row)"/>

                <div class="mb-2">
                    <label class="mr-2">Mapping</label>
                    <select v-model="mappingMode" class="bg-black border border-white p-1">
                        <option value="v1">v1</option>
                        <option value="v2">v2</option>
                        <option value="custom">Custom</option>
                    </select>

                    <button type="button" class="ml-2 border p-1" @click="showCustom = !showCustom">
                        Customize
                    </button>
                </div>

                <div v-if="mappingMode === 'custom' && showCustom" class="border p-3 mt-3">
                    <p class="mb-2">Set column letters or numbers:</p>
                    <div v-for="(v,key) in presets.v2" :key="key" class="mb-1">
                        <label class="w-40 inline-block">{{ key }}</label>
                        <input v-model="customMap[key]" placeholder="ex: B or 3"
                               class="bg-black border border-white w-20 p-1"/>
                    </div>
                    <button type="button" class="mt-2 border p-1" @click="saveCustom">Save</button>
                </div>

                <TextInput id="excel-string" class="bg-black border border-white p-1 m-3 w-full"/>
                <PrimaryButton type="button" @click="excelString">Import</PrimaryButton>
            </section>

            <InputField v-for="field in fieldsToRender" :field="field" :report="report"/>
        </form>
    </AdminLayout>
</template>
