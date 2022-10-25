<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ReportSetup from '@/Components/Reports/ReportSetup.js';
import InputField from '@/Components/Reports/InputField.vue';

let reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow; 

const props = defineProps({
    places: Object,
    events: Object,
});

console.log(props.places);

const form = useForm({
   place_id: '',
   event_id: '',
   womens: '',
   man: '',
   radway: '',
   pavement: '',
   biekpath: '',
   child_chairs: '',
   supermobility: '',
   to_center: '',
   from_center: '',
   children_self: '',
   children_passanger: '',
});

//fill data with 0 for user friendly 
let report = {};
fieldsToRender.forEach(e => {
    report[e.id] = 0; 
});

const submit = () => {
    let data = {};
    fieldsToRender.forEach(element => {
        form[element.id] = document.getElementById(element.id).value;
    });

    form['place_id'] = document.getElementById('places').value;
    form['event_id'] = document.getElementById('events').value;
    form.post(route('dashboard.reports.store'))
        .then(response => {
            console.log(response);
        });
}
</script>

<template>
    <AppLayout title="Dashboard - Create Report">
        <AuthenticationCard>
            <h2>Create Report</h2>
            <form @submit.prevent="submit">
                <div>
                    <label for="places" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Place</label>
                    <select id="places"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="place in props.places" :value="place.id">
                            {{ place.location }}
                        </option>
                    </select>
                </div>
                <div>
                    <label for="events" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Events</label>
                    <select id="events"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="event in props.events" :value="event.id">
                            {{ event.date }}
                        </option>
                    </select>
                </div>

                <InputField
                    v-for="field in fieldsToRender"
                    :field = "field"
                    :report = "report"
                />
                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Create
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </AppLayout>
</template>
