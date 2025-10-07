// Simple wrapper with TS safety
declare global {
    interface Window { dataLayer: any[]; gtag?: (...args: any[]) => void }
}
export const gaSendPageView = (params?: Partial<{
    title: string; path: string; location: string;
}>) => {
    if (!window.gtag) return;
    const title = params?.title ?? document.title;
    const path = params?.path ?? window.location.pathname + window.location.search;
    const location = params?.location ?? window.location.href;
    window.gtag('event', 'page_view', {
        page_title: title,
        page_path: path,
        page_location: location,
    });
};
