<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ReportSetup from '@/Components/Reports/ReportSetup.js';
import InputField from '@/Components/Reports/InputField.vue';

let reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow; 

const props = defineProps({
    places: Object,
    events: Object,
    report: Object,
});

const form = useForm({
   place_id: props.report.place_id,
   event_id: props.report.event_id,
   womens: props.report.womens,
   man: props.report.man,
   radway: props.report.radway,
   pavement: props.report.pavement,
   biekpath: props.report.biekpath,
   child_chairs: props.report.child_chairs,
   supermobility: props.report.supermobility,
   to_center: props.report.to_center,
   from_center: props.report.from_center,
   children_self: props.report.children_self,
   children_passanger: props.report.children_passanger,
});

const submit = () => {
    fieldsToRender.forEach(element => {
        form[element.id] = document.getElementById(element.id).value;
    });

    form['place_id'] = document.getElementById('places').value;
    form['event_id'] = document.getElementById('events').value;
    form.patch(route('dashboard.reports.update',{id:props.report.id}))
        .then(response => {
            console.log(response);
        });
}
</script>

<template>
    <AdminLayout title="Dashboard - Create Report">
        <AuthenticationCard>
            <h2>Create Report</h2>
            <form @submit.prevent="submit">
                <div>
                    <label for="places" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Place</label>
                    <select id="places"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option
                            v-for="place in props.places" 
                            :value="place.id"
                            :selected="place.id === props.report.place_id ? true : false">
                                {{ place.location }}
                        </option>
                    </select>
                </div>
                <div>
                    <label for="events" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Events</label>
                    <select id="events"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option 
                        v-for="event in props.events" 
                        :value="event.id"
                        :selected="event.id === props.report.event_id ? true : false">
                            {{ event.date }}
                        </option>
                    </select>
                </div>

                <InputField
                    v-for="field in fieldsToRender"
                    :field = "field"
                    :report = "props.report"
                />
                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Edit
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </AdminLayout>
</template>
