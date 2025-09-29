<template>
    <aside class="border border-zinc-800 bg-zinc-900 p-3 sticky top-6">
        <nav class="space-y-1">
            <a href="/dashboard" class="block">
                <div
                    class="flex items-center gap-3 px-3 py-2 transition border border-transparent hover:bg-zinc-800 text-zinc-300"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12h18M3 6h18M3 18h18" />
                    </svg>
                    <span class="text-sm font-medium">Overview</span>
                </div>
            </a>

            <a href="/dashboard/events" class="block">
                <div
                    class="flex items-center gap-3 px-3 py-2 transition border border-transparent hover:bg-zinc-800 text-zinc-300"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7V5m8 2V5M4 9h16M7 11h10M7 15h6" />
                    </svg>
                    <span class="text-sm font-medium">Events</span>
                </div>
            </a>

            <a href="/dashboard/places" class="block">
                <div
                    class="flex items-center gap-3 px-3 py-2 transition border border-transparent hover:bg-zinc-800 text-zinc-300"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21s-6-4.5-6-9a6 6 0 1112 0c0 4.5-6 9-6 9z M12 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    <span class="text-sm font-medium">Places</span>
                </div>
            </a>

            <a href="/dashboard/reports" class="block">
                <div
                    class="flex items-center gap-3 px-3 py-2 transition border border-transparent hover:bg-zinc-800 text-zinc-300"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16v12H4z M8 10l3 3 5-5" />
                    </svg>
                    <span class="text-sm font-medium">Reports</span>
                </div>
            </a>

            <!-- Updated API link -->
            <a href="/dashboard/apis" class="block">
                <div
                    class="flex items-center gap-3 px-3 py-2 transition border border-transparent hover:bg-zinc-800 text-zinc-300"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round">
                        <!-- left curly brace -->
                        <path d="M6 4c-1 0-2 1-2 2v3c0 1-1 2-2 2 1 0 2 1 2 2v3c0 1 1 2 2 2" />
                        <!-- right curly brace -->
                        <path d="M18 4c1 0 2 1 2 2v3c0 1 1 2 2 2-1 0-2 1-2 2v3c0 1-1 2-2 2" />
                    </svg>
                    <span class="text-sm font-medium">API keys</span>
                </div>
            </a>
        </nav>
    </aside>
</template>

<script setup lang="ts">
</script>
