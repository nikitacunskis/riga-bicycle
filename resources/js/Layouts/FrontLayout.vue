<template>
    <!-- Scene background with grid + aurora -->
    <div class="relative min-h-screen text-gray-900 overflow-hidden bg-base">
        <!-- Static glow/aurora layers (non-interactive) -->
        <div aria-hidden="true" class="pointer-events-none">
            <div class="aurora aurora-a"></div>
            <div class="aurora aurora-b"></div>
            <div class="aurora aurora-c"></div>
            <div class="light-rays"></div>
            <div class="vignette"></div>
            <div class="grain"></div>
        </div>

        <!-- Subtle grid overlay -->
        <div aria-hidden="true" class="absolute inset-0 bg-grid"></div>

        <header
            class="site-header top-0 z-50 backdrop-blur-md bg-white/70
           shadow-[0_2px_20px_-10px_rgba(16,185,129,0.35)]
           ring-1 ring-white/60"
        >
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="keyline"></div>
                <HeaderNav />
            </div>
        </header>


        <!-- Framed content card -->
        <main class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div
                class="relative rounded-3xl bg-white/75 backdrop-blur-xl ring-1 ring-white/60 shadow-2xl overflow-hidden card-frame"
            >
                <!-- Static rim light -->
                <div aria-hidden="true"></div>
                <!-- Inner bevel + hairline -->
                <div aria-hidden="true" class="bevel"></div>
                <div aria-hidden="true" class="hairline"></div>

                <!-- content slot -->
                <div class="relative z-10 p-5 sm:p-8 lg:p-10">
                    <slot />
                </div>
            </div>
        </main>

        <!-- Footer with gradient rail -->
        <footer class="mt-10">
            <div class="w-full mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-t-2xl bg-gradient-to-r from-emerald-100/70 via-white/70 to-emerald-100/70 ring-1 ring-white/60 backdrop-blur-xl footer-rail">
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
/* ====== Base gradient (kept palette) ====== */
.bg-base {
    background-image:
        radial-gradient(1200px 600px at 85% -10%, rgba(16,185,129,0.18), transparent 60%),
        radial-gradient(900px 500px at -10% 10%, rgba(52,211,153,0.16), transparent 55%),
        linear-gradient(180deg, #f0fdf4 0%, #ffffff 45%, #ecfdf5 100%);
}

/* ====== Subtle grid overlay (low-contrast) ====== */
.bg-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(to right, rgba(0,0,0,0.04) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0,0,0,0.04) 1px, transparent 1px);
    background-size: 24px 24px;
    mask-image: radial-gradient(75% 75% at 50% 10%, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
    pointer-events: none;
}

/* ====== Static aurora blobs ====== */
.aurora {
    position: absolute;
    filter: blur(44px);
    opacity: 0.55;
    mix-blend-mode: multiply;
    transform: translateZ(0);
}
.aurora-a { top: -12rem; left: -8rem; width: 40rem; height: 40rem; background: radial-gradient(circle at 30% 30%, rgba(16,185,129,0.35), transparent 60%); }
.aurora-b { top: -6rem; right: -10rem; width: 46rem; height: 46rem; background: radial-gradient(circle at 70% 40%, rgba(5,150,105,0.30), transparent 65%); }
.aurora-c { bottom: -14rem; left: 20%; width: 50rem; height: 50rem; background: radial-gradient(circle at 50% 50%, rgba(110,231,183,0.28), transparent 60%); }

/* ====== Light rays (static conic gradients) ====== */
.light-rays {
    position: absolute;
    inset: -10% -10% -10% -10%;
    pointer-events: none;
    background:
        conic-gradient(from 210deg at 15% 0%, rgba(16,185,129,0.18) 0deg, transparent 40deg),
        conic-gradient(from 320deg at 95% 5%, rgba(5,150,105,0.12) 0deg, transparent 55deg);
    mix-blend-mode: screen;
    opacity: 0.6;
}

/* ====== Global vignette to anchor edges ====== */
.vignette {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: radial-gradient(120% 90% at 50% 0%, transparent 40%, rgba(0,0,0,0.06) 80%, rgba(0,0,0,0.10) 100%);
}

/* ====== Fine grain (static, subtle) ====== */
.grain {
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: 0.08;
    mix-blend-mode: overlay;
    background-image: url("data:image/svg+xml;utf8,\
<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'>\
<filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='2' stitchTiles='stitch'/></filter>\
<rect width='160' height='160' filter='url(%23n)' opacity='0.5'/>\
</svg>");
    background-size: 200px 200px;
}

/* ====== Header polish ====== */
.site-header {
    position: sticky;
    top: 0;
    border-bottom: 1px solid rgba(255,255,255,0.7);
}
.site-header .keyline {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background:
        linear-gradient(to right, rgba(16,185,129,0.35), rgba(16,185,129,0.0) 20% 80%, rgba(16,185,129,0.35));
    mask: linear-gradient(black, black) top/100% 2px no-repeat;
    opacity: 0.8;
}

/* ====== Card frame with depth (no motion) ====== */
.card-frame {
    /* subtle inner shadow for depth */
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.6),
        0 40px 60px -30px rgba(16,185,129,0.25),
        0 12px 24px -16px rgba(0,0,0,0.15);
}

/* Inner bevel layer for glass feel */
.bevel {
    position: absolute;
    inset: 0;
    border-radius: 1.5rem;
    background:
        linear-gradient(180deg, rgba(255,255,255,0.65), rgba(255,255,255,0.35) 30%, rgba(255,255,255,0.25) 100%);
    mix-blend-mode: overlay;
    opacity: 0.45;
    pointer-events: none;
}

/* Hairline (double-border effect) */
.hairline {
    position: absolute;
    inset: 0;
    border-radius: 1.5rem;
    pointer-events: none;
    background:
        radial-gradient(110% 110% at 0% 0%, rgba(255,255,255,0.9), transparent 60%) top left / 50% 50% no-repeat,
        radial-gradient(110% 110% at 100% 100%, rgba(16,185,129,0.18), transparent 60%) bottom right / 50% 50% no-repeat;
    outline: 1px solid rgba(255,255,255,0.6);
    outline-offset: -1px;
}

/* ====== Footer rail detailing ====== */
.footer-rail {
    border: 1px solid rgba(255,255,255,0.65);
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.6),
        0 -10px 30px -20px rgba(16,185,129,0.35);
}

/* ====== Accessibility: no transitions or animations ====== */
*, *::before, *::after {
    transition: none !important;
    animation: none !important;
}

@media (max-width: 768px) {

}
</style>
