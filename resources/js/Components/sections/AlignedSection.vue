<template>
    <section class="py-16 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto">
            <header v-if="title || subtitle" class="mb-10 text-center">
                <h2 v-if="title" class="text-3xl font-bold text-green-800">{{ title }}</h2>
                <p v-if="subtitle" class="mt-3 text-gray-700">{{ subtitle }}</p>
            </header>

            <!-- One row per item -->
            <div class="space-y-12">
                <div v-for="(item, i) in items" :key="i"
                     class="grid lg:grid-cols-12 gap-12 items-start">
                    <!-- Left: image -->
                    <div class="lg:col-span-5">
                        <div class="aspect-video w-full rounded-3xl overflow-hidden">
                            <img v-if="item.image" :src="item.image" :alt="item.imageAlt || ''"
                                 class="h-full w-full object-cover" />
                        </div>
                    </div>

                    <!-- Right: text -->
                    <div class="lg:col-span-7 space-y-4">
                        <h3 v-if="item.heading" class="text-2xl font-semibold text-green-800">
                            {{ item.heading }}
                        </h3>
                        <p v-if="item.body" class="text-gray-700">
                            {{ item.body }}
                        </p>

                        <ul v-if="item.points?.length" class="grid sm:grid-cols-2 gap-3 text-gray-700">
                            <li v-for="(p, idx) in item.points" :key="idx" class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-emerald-100 ring-1 ring-white/60 flex items-center justify-center">
                                    {{ p.icon || '✅' }}
                                </div>
                                <span>{{ p.text }}</span>
                            </li>
                        </ul>

                        <div v-if="item.pills?.length" class="flex flex-wrap gap-2">
              <span v-for="(tag, t) in item.pills" :key="t"
                    class="px-3 py-1 rounded-full bg-emerald-50 ring-1 ring-emerald-200 text-emerald-900">
                {{ tag }}
              </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({
    title: String,
    subtitle: String,
    // Keep it simple; validate structure in runtime if needed
    items: { type: Array, required: true }
})
</script>
