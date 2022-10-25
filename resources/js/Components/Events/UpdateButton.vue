<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    event: Object,
});
const form = useForm({
    id: props.event.id,
});


const submit = () => {
    form.get(route('dashboard.events.edit', {'id':props.event.id}));
};

</script>
<template>

    <form @submit.prevent="submit">
        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Edit
        </PrimaryButton>
    </form>
</template>
<script>
</script>