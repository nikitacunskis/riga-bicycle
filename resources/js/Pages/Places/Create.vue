<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    location: '',
    coordinates: '',
    lat: '',
    lng: '',
});

const submit = () => {
    console.log('posting to', route('dashboard.places.store'), form.data())
    form.post(route('dashboard.places.store'), {
        onSuccess: () => console.info('✅ created'),
        onError:   (e) => console.warn('❌ validation', e),
    })
}

</script>

<template>
    <AdminLayout title="Dashboard - Create Place">
        <h2>Create Place</h2>
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="location" value="Location" class="text-white"/>
                <TextInput
                    id="location"
                    v-model="form.location"
                    type="text"
                    class="mt-1 block w-full bg-black text-white"
                    required
                    autofocus
                    autocomplete="location"
                />
                <InputError class="mt-2" :message="form.errors.location" />
            </div>

            <div class="mt-4">
                <InputLabel for="lat" value="Latitude" class="text-white" />
                <TextInput
                    id="lat"
                    v-model="form.lat"
                    type="text"
                    class="mt-1 block w-full bg-black text-white"
                    required
                />
                <InputError class="mt-2" :message="form.errors.lat" />
            </div>

            <div class="mt-4">
                <InputLabel for="lng" value="Longtude" class="text-white" />
                <TextInput
                    id="lng"
                    v-model="form.lng"
                    type="text"
                    class="mt-1 block w-full bg-black text-white"
                    required
                />
                <InputError class="mt-2" :message="form.errors.lng" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create
                </PrimaryButton>
            </div>
        </form>
    </AdminLayout>
</template>
