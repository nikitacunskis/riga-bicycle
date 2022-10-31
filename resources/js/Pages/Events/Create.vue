<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    date: '',
    weather: '',
});

let error = '';

const todayDate = () => {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    form.date = yyyy + '-' + mm + '-' + dd;
}

const dateCanBeFound = () => {
    let formDate = new Date(form.date).toISOString();
    var today = new Date();
    if(formDate != 'NaN')
    {
        if(today <= formDate)
        {
            return true;
        }
        error = "Date can't be found if you set future date";
    }
    return false;
}

const getWeather = () => {
    if(form.date !== '' || dateCanBeFound())
    {
        axios.post('/dashboard/weather/get/', {date: form.date})
            .then(response => {
                //id on BE isn't static
                let id = Object.keys(response.data)[Object.keys(response.data).length - 1];

                let data = JSON.parse(response.data[id].json_data);
                let temperature = data.main.temp - 273.15; 
                let temperature_max = data.main.temp_max - 273.15; 
                form.weather = "Gaisa temperatūra: " + temperature.toFixed(2) + " " + data.weather[0].description + "(Maksimālā temperatūra: " + temperature_max.toFixed(2) + " )";
        });

    }
}

const autoComplete = () => {
    todayDate();
    getWeather();
}

const submit = () => {
    form.post(route('dashboard.events.store'), {
        onFinish: () => console.log('event created'),
    });
};

autoComplete();
</script>

<template>
    <AdminLayout title="Dashboard - Create Event">
        <AuthenticationCard>
            <h2>Create Event</h2>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="date" value="Date" />
                    <TextInput
                        id="date"
                        v-model="form.date"
                        type="date"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="date"
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
                        Create
                    </PrimaryButton>
                </div>
            </form>
            {{ error }}
        </AuthenticationCard>
    </AdminLayout>
</template>
