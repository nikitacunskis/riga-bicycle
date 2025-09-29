<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    event: Object,
});
const form = useForm({
    id: props.event.id,
});


const submit = () => {
    form.delete(route('dashboard.events.destroy', {'id':props.event.id}), {
        onFinish: () => console.log('event destroyed'),
    });
};
</script>
<template>
    <form @submit.prevent="submit">
        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Delete
        </PrimaryButton>
    </form>
</template>
