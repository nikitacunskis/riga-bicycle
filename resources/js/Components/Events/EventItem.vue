<script setup>
import UpdateButton from './UpdateButton.vue'
import DeleteButton from './DeleteButton.vue'

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <tr
        class="group transition hover:bg-gray-50/80 dark:hover:bg-white/5 focus-within:bg-gray-50/80"
    >
        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100 align-middle">
            {{ props.event.date }}
        </td>

        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 align-middle">
            {{ props.event.weather }}
        </td>

        <td class="px-4 py-2 align-middle">
            <div class="flex items-center gap-2">
                <UpdateButton :event="props.event" />
                <DeleteButton :event="props.event" />
            </div>
        </td>
    </tr>
</template>
