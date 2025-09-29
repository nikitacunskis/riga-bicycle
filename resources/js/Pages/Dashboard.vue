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
            <button
                class="px-3 py-2  border border-zinc-700 text-zinc-200 bg-zinc-900 hover:bg-zinc-800 transition"
                @click="$inertia.visit(route('dashboard.events'))"
            >
                Create Event
            </button>
            <button
                class="px-3 py-2  bg-zinc-100 text-zinc-900 hover:opacity-90 transition"
                @click="$inertia.visit(route('dashboard.reports'))"
            >
                New Report
            </button>
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
            <div class="mt-4 grid sm:grid-cols-2 gap-3">
                <button
                    class="group px-4 py-3 rounded-2xl border border-zinc-700 text-sm text-left text-zinc-200
                 hover:bg-zinc-800 transition"
                    @click="$inertia.visit(route('dashboard.events'))"
                >
                    <div class="font-medium flex items-center justify-between">
                        Plan Community Event
                        <span class="opacity-0 group-hover:opacity-100 transition">→</span>
                    </div>
                    <div class="text-xs text-zinc-400">Add details, capacity, and shareables.</div>
                </button>
                <button
                    class="group px-4 py-3 rounded-2xl border border-zinc-700 text-sm text-left text-zinc-200
                 hover:bg-zinc-800 transition"
                    @click="$inertia.visit(route('dashboard.reports'))"
                >
                    <div class="font-medium flex items-center justify-between">
                        File/Review Reports
                        <span class="opacity-0 group-hover:opacity-100 transition">→</span>
                    </div>
                    <div class="text-xs text-zinc-400">Keep issues tracked and resolved.</div>
                </button>
            </div>
        </section>

    </AdminLayout>
</template>
