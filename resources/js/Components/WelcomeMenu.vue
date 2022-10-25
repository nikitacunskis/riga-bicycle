<script setup>
    import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <table>
        <tr>
            <td class="px-4 py-2 w-20">
                <Link href="/dashboard/places" method="get" as="button" type="button">Places</Link>
            </td>
            <td class="px-4 py-2 w-20">
                <Link href="/dashboard/counts" method="get" as="button" type="button">Counts</Link>
            </td>
        </tr>
    </table>
</template>