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

const form = useForm({
    location: '',
    coordinates: '',
});

const submit = () => {
    form.post(route('dashboard.places.store'), {
        onFinish: () => console.log('place created'),
    });
};
</script>

<template>
    <AppLayout title="Dashboard - Create Place">
        <AuthenticationCard>
            <h2>Create Place</h2>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="location" value="Location" />
                    <TextInput
                        id="location"
                        v-model="form.location"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="location"
                    />
                    <InputError class="mt-2" :message="form.errors.location" />
                </div>

                <div class="mt-4">
                    <InputLabel for="coordinates" value="Coordinates" />
                    <TextInput
                        id="coordinates"
                        v-model="form.coordinates"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.coordinates" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Create
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </AppLayout>
</template>
