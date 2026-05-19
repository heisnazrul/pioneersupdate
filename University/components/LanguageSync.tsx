"use client";

import { useEffect } from 'react';

/**
 * Tiny client component that immediately syncs `document.dir` and `document.lang`
 * on mount, preventing any flash of wrong direction before JS hydrates.
 * Include this once inside ClientLayout.
 */
export default function LanguageSync() {
    useEffect(() => {
        const lang = localStorage.getItem('uni_language') === 'ar' ? 'ar' : 'en';
        document.documentElement.lang = lang;
        document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
    }, []);
    return null;
}
