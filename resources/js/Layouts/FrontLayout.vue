<template>
    <!-- Scene background with grid + aurora -->
    <div class="relative min-h-screen text-gray-900 overflow-hidden bg-base">
        <!-- Glow/aurora layers (non-interactive) -->
        <div aria-hidden="true" class="pointer-events-none">
            <div class="aurora aurora-a"></div>
            <div class="aurora aurora-b"></div>
            <div class="aurora aurora-c"></div>
        </div>

        <!-- Subtle grid overlay -->
        <div aria-hidden="true" class="absolute inset-0 bg-grid"></div>

        <!-- Header (glassy bar) -->
        <header
            class="sticky top-0 z-50 backdrop-blur-md bg-white/70 shadow-[0_2px_20px_-10px_rgba(16,185,129,0.35)] ring-1 ring-white/60"
        >
            <HeaderNav />
        </header>

        <!-- Framed content card -->
        <main class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div
                class="relative rounded-3xl bg-white/75 backdrop-blur-xl ring-1 ring-white/60 shadow-2xl
               animate-fadein overflow-hidden"
            >
                <!-- animated rim light -->
                <div aria-hidden="true" class="glow-rim"></div>

                <!-- content slot -->
                <div class="relative z-10 p-5 sm:p-8 lg:p-10">
                    <slot />
                </div>
            </div>
        </main>

        <!-- Footer with gradient rail -->
        <footer class="mt-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-2xl bg-gradient-to-r from-emerald-100/70 via-white/70 to-emerald-100/70 ring-1 ring-white/60 backdrop-blur-xl">
                    <SiteFooter />
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import HeaderNav from '../Components/HeaderNav.vue'
import SiteFooter from '../Components/SiteFooter.vue'
</script>

<style scoped>
/* ====== Base gradient ====== */
.bg-base {
    /* soft diagonal wash in your green range */
    background-image:
        radial-gradient(1200px 600px at 85% -10%, rgba(16,185,129,0.18), transparent 60%),
        radial-gradient(900px 500px at -10% 10%, rgba(52,211,153,0.16), transparent 55%),
        linear-gradient(180deg, #f0fdf4 0%, #ffffff 45%, #ecfdf5 100%);
}

/* ====== Subtle grid overlay (low-contrast) ====== */
.bg-grid {
    background-image:
        linear-gradient(to right, rgba(0,0,0,0.04) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0,0,0,0.04) 1px, transparent 1px);
    background-size: 24px 24px;
    mask-image: radial-gradient(75% 75% at 50% 10%, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
}

/* ====== Aurora blobs ====== */
.aurora {
    position: absolute;
    filter: blur(40px);
    opacity: 0.55;
    mix-blend-mode: multiply;
    transform: translateZ(0);
}
.aurora-a { top: -12rem; left: -8rem; width: 40rem; height: 40rem; background: radial-gradient(circle at 30% 30%, rgba(16,185,129,0.35), transparent 60%); }
.aurora-b { top: -6rem; right: -10rem; width: 46rem; height: 46rem; background: radial-gradient(circle at 70% 40%, rgba(5,150,105,0.30), transparent 65%); }
.aurora-c { bottom: -14rem; left: 20%; width: 50rem; height: 50rem; background: radial-gradient(circle at 50% 50%, rgba(110,231,183,0.28), transparent 60%); }

/* Gentle drift animation (reduced for motion-sensitive users) */
@media (prefers-reduced-motion: no-preference) {
    .aurora-a { animation: floatA 18s ease-in-out infinite alternate; }
    .aurora-b { animation: floatB 22s ease-in-out infinite alternate; }
    .aurora-c { animation: floatC 26s ease-in-out infinite alternate; }
}
@keyframes floatA { from { transform: translate(-10px, 0); } to { transform: translate(10px, 8px); } }
@keyframes floatB { from { transform: translate(8px, -6px); } to { transform: translate(-8px, 4px); } }
@keyframes floatC { from { transform: translate(0, 10px); } to { transform: translate(8px, -6px); } }

/* ====== Rim glow around the content card ====== */
.glow-rim {
    position: absolute;
    inset: -1px;
    pointer-events: none;
    background:
        conic-gradient(from 180deg at 50% 50%,
        rgba(16,185,129,0.0) 0deg,
        rgba(16,185,129,0.25) 90deg,
        rgba(16,185,129,0.0) 180deg,
        rgba(16,185,129,0.25) 270deg,
        rgba(16,185,129,0.0) 360deg);
    mask: radial-gradient(closest-side, transparent 97%, black 100%);
    border-radius: 1.5rem;
    opacity: .7;
}
@media (prefers-reduced-motion: no-preference) {
    .glow-rim { animation: spin 16s linear infinite; }
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ====== Fade-in for slot content ====== */
@keyframes fadein {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-fadein { animation: fadein .6s ease-out both; }
</style>
