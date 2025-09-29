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


const props = defineProps({
    place: Object,
});

const form = useForm({
    location: props.place.location,
    coordinates: props.place.coordinates,
});

const submit = () => {
    form.patch(route('dashboard.places.update', {id:props.place.id}), {
        onFinish: () => console.log('place updated'),
    });
};
</script>

<template>
    <AdminLayout title="Dashboard - Create Place">
        <AuthenticationCard>
            <h2>Edit Place</h2>
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
                        Edit
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </AdminLayout>
</template>
