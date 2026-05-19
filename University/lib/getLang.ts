import { cookies } from 'next/headers';

/**
 * Server-side helper: reads the `uni_language` cookie and returns 'en' or 'ar'.
 * Use this in every server component / page that needs the current language.
 */
export async function getLang(): Promise<'en' | 'ar'> {
    const cookieStore = await cookies();
    const val = (cookieStore.get('uni_language')?.value || 'en').toLowerCase();
    return val === 'ar' ? 'ar' : 'en';
}

export type Lang = 'en' | 'ar';
