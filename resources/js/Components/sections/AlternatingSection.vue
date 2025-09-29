<template>
    <section class="py-16 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <header v-if="title || subtitle" class="mb-10 text-center">
                <h2 v-if="title" class="text-3xl font-bold text-green-800">{{ title }}</h2>
                <p v-if="subtitle" class="mt-3 text-gray-700">{{ subtitle }}</p>
            </header>

            <div class="grid gap-12">
                <article v-for="(item, i) in items" :key="i"
                         class="grid lg:grid-cols-12 gap-10 items-center">
                    <!-- IMAGE (alternates per row) -->
                    <div
                        class="lg:col-span-5"
                        :class="(i % 2 === 0) ? 'order-1' : 'order-2 lg:order-1'">
                        <div class="aspect-video w-full rounded-3xl bg-gray-200 ring-1 ring-white/60 shadow-inner
                        flex items-center justify-center text-gray-500">
                            <span>{{ item.imageAlt || 'Attēla vietturis' }}</span>
                        </div>
                    </div>

                    <!-- TEXT -->
                    <div
                        class="lg:col-span-7"
                        :class="(i % 2 === 0) ? 'order-2' : 'order-1 lg:order-2'">
                        <h3 v-if="item.heading" class="text-2xl font-semibold text-green-800">
                            {{ item.heading }}
                        </h3>
                        <p v-if="item.body" class="mt-4 text-gray-700">
                            {{ item.body }}
                        </p>

                        <!-- Optional icon bullets -->
                        <ul v-if="item.points?.length" class="mt-6 grid sm:grid-cols-2 gap-3 text-gray-700">
                            <li v-for="(p, idx) in item.points" :key="idx" class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-xl bg-emerald-100 ring-1 ring-white/60 flex items-center justify-center">
                                    {{ p.icon || '✅' }}
                                </div>
                                <span>{{ p.text }}</span>
                            </li>
                        </ul>

                        <!-- Optional pills -->
                        <div v-if="item.pills?.length" class="mt-6 flex flex-wrap gap-2">
              <span v-for="(tag, t) in item.pills" :key="t"
                    class="px-3 py-1 rounded-full bg-emerald-50 ring-1 ring-emerald-200 text-emerald-900">
                {{ tag }}
              </span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({
    title: String,
    subtitle: String,
    items: { type: Array, required: true },
})
</script>
