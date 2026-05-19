/**
 * useLang — reactive language hook for client components.
 *
 * Reads from localStorage on mount, then stays in sync via:
 *   1. 'uni:langchange' — custom event dispatched by Navbar in the same tab
 *   2. 'storage'       — native event for cross-tab updates
 *
 * Usage:
 *   const { isAr } = useLang();
 */
import { useState, useEffect } from 'react';

const STORAGE_KEY = 'uni_language';
export const LANG_CHANGE_EVENT = 'uni:langchange';

export function getLangFromStorage(): 'en' | 'ar' {
    if (typeof window === 'undefined') return 'en';
    return (localStorage.getItem(STORAGE_KEY) || 'en') as 'en' | 'ar';
}

export function useLang() {
    const [lang, setLang] = useState<'en' | 'ar'>('en');

    useEffect(() => {
        // Initial read
        setLang(getLangFromStorage());

        const sync = () => setLang(getLangFromStorage());

        // Same-tab updates (from Navbar)
        window.addEventListener(LANG_CHANGE_EVENT, sync);
        // Cross-tab updates
        window.addEventListener('storage', sync);

        return () => {
            window.removeEventListener(LANG_CHANGE_EVENT, sync);
            window.removeEventListener('storage', sync);
        };
    }, []);

    return { lang, isAr: lang === 'ar' };
}
