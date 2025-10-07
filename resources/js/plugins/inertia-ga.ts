import { gaSendPageView } from '@/js/lib/ga';

// Fire initial page_view after first paint
export const installInertiaGA = () => {
    // Initial hit
    if (document.readyState === 'complete') {
        gaSendPageView();
    } else {
        window.addEventListener('load', () => gaSendPageView());
    }

    // Inertia SPA navigations:
    // Inertia dispatches DOM events you can rely on: 'inertia:navigate' and 'inertia:finish'
    // 'inertia:navigate' fires when a new page is set.
    document.addEventListener('inertia:navigate', () => {
        // Title may change a tick later; queue microtask for accuracy
        queueMicrotask(() => gaSendPageView());
    });

    // (Optional) also listen to finish to be extra safe if you do async title changes
    document.addEventListener('inertia:finish', () => {
        queueMicrotask(() => gaSendPageView());
    });
};
