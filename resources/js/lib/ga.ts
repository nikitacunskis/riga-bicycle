// resources/js/lib/ga.ts
declare global {
    interface Window { gtag?: (...args: any[]) => void }
}
const isLocal = location.hostname === 'localhost' || location.hostname === '127.0.0.1';

export const gaSendPageView = (params?: Partial<{ title: string; path: string; location: string }>) => {
    if (!window.gtag) return;
    const title = params?.title ?? document.title;
    const path = params?.path ?? window.location.pathname + window.location.search;
    const loc = params?.location ?? window.location.href;

    window.gtag('event', 'page_view', {
        page_title: title,
        page_path: path,
        page_location: loc,
        debug_mode: isLocal,
    });
};

export const gaEvent = (name: string, params: Record<string, any> = {}) => {
    if (!window.gtag) return;
    window.gtag('event', name, { ...params, debug_mode: isLocal });
};
