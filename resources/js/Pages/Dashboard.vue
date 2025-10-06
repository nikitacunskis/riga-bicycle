<script setup>
    import AdminLayout from '../Layouts/AdminLayout.vue'
    import FancyMenu from '../Components/FancyMenu.vue'
    import UserCard from '../Components/UserCard.vue'
    import { usePage } from '@inertiajs/vue3'
    import { computed } from 'vue'
    const page = usePage()
    const user = computed(() => page.props.value.auth?.user ?? null)
</script>

<template>
    <AdminLayout title="Dashboard">
        <!-- LEFT SIDEBAR CONTENT -->
        <template #sidebar>
            <div class="space-y-4">
                <!-- Profile first -->
                <UserCard :user="user" />
                <!-- Then the menu -->
                <FancyMenu active="overview" />
            </div>
        </template>

        <!-- OPTIONAL ACTIONS IN HEADER (RIGHT) -->
        <template #actions>
        </template>

        <!-- MAIN CONTENT CARDS -->
        <section
            class="border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30
             [background-image:linear-gradient(180deg,rgba(255,255,255,0.02),rgba(0,0,0,0))]"
        >
            <header class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Welcome</h3>
                <span class="text-xs text-zinc-400">"Pilsēta cilvēkiem veloskaitīšana" Control Center</span>
            </header>
            <p class="text-sm text-zinc-300">
                Use the left sidebar to manage members, programs, events, and reports.
                Keep it lean: fast approvals, clean records, transparent updates.
            </p>
        </section>

    </AdminLayout>
</template>
