    <!-- resources/js/Components/UserCard.vue -->
    <script setup>
        const props = defineProps({ user: { type: Object, default: null } })
    </script>

    <template>
        <section class="border border-zinc-800 bg-zinc-900 text-zinc-100">
            <!-- Top: avatar + identity -->
            <div class="px-5 py-4 flex items-center gap-4">
                <div
                    class="h-12 w-12 bg-zinc-800 grid place-items-center text-zinc-300 text-sm font-semibold
                   ring-1 ring-zinc-700 select-none"
                    :style="user?.profile_photo_url ? '' : ''"
                >
                    <img
                        v-if="user?.profile_photo_url"
                        :src="user.profile_photo_url"
                        :alt="user?.name ?? 'User'"
                        class="h-full w-full object-cover"
                    />
                    <template v-else>
                        {{ (user?.name ?? 'U').split(' ').map(p=>p[0]).join('').slice(0,2).toUpperCase() }}
                    </template>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="text-[11px] uppercase tracking-wider text-zinc-400">Signed in as</div>
                    <div class="text-[17px] font-semibold leading-tight truncate">{{ user?.name ?? 'Account' }}</div>
                    <div class="text-xs text-zinc-400 truncate">{{ user?.email ?? '' }}</div>
                </div>
            </div>

            <!-- Accent rule -->
            <div class="h-px bg-gradient-to-r from-transparent via-zinc-700 to-transparent"></div>

            <!-- Logout -->
            <form class="px-5 pb-5" @submit.prevent="$inertia.post(route('logout'))">
                <button
                    class="w-full px-3 py-2 bg-zinc-100 text-zinc-900 text-sm hover:opacity-90 transition"
                >Log Out</button>
            </form>
        </section>
    </template>
