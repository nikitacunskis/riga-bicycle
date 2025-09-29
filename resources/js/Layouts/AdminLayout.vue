<!-- resources/js/Layouts/AdminLayout.vue -->
<script setup>
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import UserCard from '../Components/UserCard.vue'
import FancyMenu from '../Components/FancyMenu.vue'

const props = defineProps({
    title: { type: String, default: '' },
})

// Pull user from shared Inertia props
const page = usePage()
const user = computed(() => page.props.value?.auth?.user ?? null)
</script>

<template>
    <div class="min-h-screen bg-black text-white">
        <Head :title="title" />

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-12 gap-6">
                <!-- Sidebar -->
                <aside class="col-span-12 lg:col-span-3 sticky top-6 self-start">
                    <div class="space-y-4">
                        <UserCard :user="user" />
                        <FancyMenu active="overview" />
                    </div>
                </aside>

                <!-- Main -->
                <section class="col-span-12 lg:col-span-9 space-y-6">
                    <!-- Single compact bar for title + actions. Renders only if title or actions exist -->
                    <header
                        v-if="title || $slots.actions"
                        class="border p-3"
                    >
                        <div class="flex items-center justify-between">
                            <h1 class="text-2xl font-semibold tracking-[-0.01em]">
                                {{ title }}
                            </h1>
                            <div class="flex items-center">
                                <slot name="actions" />
                            </div>
                        </div>
                    </header>

                    <main class="space-y-6">
                        <slot />
                    </main>
                </section>
            </div>
        </div>
    </div>
</template>
