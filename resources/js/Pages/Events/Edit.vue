<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';   
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Weather from '@/Controller/Weather.js';


const props = defineProps({
    event: Object,
});

const form = useForm({
    date: props.event.date,
    weather: props.event.weather,
});

const todayDate = () => {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    form.date = yyyy + '-' + mm + '-' + dd;
}


const getWeather = () => {
    axios.post('/dashboard/weather/get/', {date: form.date})
        .then(response => {
            let weather = new Weather();
            let data = JSON.parse(response.data[0].json_data);
            let temperature = data.main.temp - 273.15; 
            let temperature_max = data.main.temp_max - 273.15; 
            form.weather = "Gaisa temperatūra: " + temperature.toFixed(2) + " " + weather.description[data.weather[0].description] + "(Maksimālā temperatūra: " + temperature_max.toFixed(2) + " )";
        });
}

const submit = () => {
    form.patch(route('dashboard.events.update', {id:props.event.id}), {
        onFinish: () => console.log('event updated'),
    });
};
</script>

<template>
    <AppLayout title="Dashboard - Create Event">
        <AuthenticationCard>
            <h2>Edit Event</h2>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="location" value="Date" />
                    <TextInput
                        id="date"
                        v-model="form.date"
                        type="date"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />

                    <PrimaryButton @click="todayDate" type="button">
                        Today
                    </PrimaryButton>
                    <InputError class="mt-2" :message="form.errors.date" />
                </div>

                <div class="mt-4">
                    <InputLabel for="weather" value="Weather" />
                    <TextInput
                        id="weather"
                        v-model="form.weather"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <PrimaryButton @click="getWeather" type="button">
                        Get weather
                    </PrimaryButton>
                    <InputError class="mt-2" :message="form.errors.weather" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Edit
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </AppLayout>
</template>
