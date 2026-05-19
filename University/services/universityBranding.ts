import { API_URL } from './utils';

export interface BrandingNavItem {
    label?: string;
    ar_label?: string;
    url?: string;
}

export interface BrandingLanguage {
    code: string;
    label?: string;
    ar_label?: string;
    flag?: string;
}

export interface UniversityBranding {
    app?: string;
    header?: {
        logo?: {
            main?: string;
            ar?: string;
        };
        main_nav?: BrandingNavItem[];
        top_nav?: BrandingNavItem[];
        languages?: BrandingLanguage[];
        buttons?: {
            account?: {
                label?: string;
                ar_label?: string;
                url?: string;
            };
        };
    };
    footer?: {
        columns?: Array<{
            title?: string;
            ar_title?: string;
            items?: BrandingNavItem[];
        }>;
        description?: string;
        ar_description?: string;
        logo?: string;
        social?: Array<{
            platform?: string;
            url?: string;
        }>;
        brand?: string;
        ar_brand?: string;
        copyright?: string;
        ar_copyright?: string;
    };
    mobile?: {
        nav?: BrandingNavItem[];
        drawer_links?: BrandingNavItem[];
        drawer_legal?: BrandingNavItem[];
    };
}

interface BrandingApiResponse {
    branding?: UniversityBranding;
}

export async function getUniversityBranding(): Promise<UniversityBranding | null> {
    try {
        const res = await fetch(`${API_URL}/university/branding`, { cache: 'no-store' });
        if (!res.ok) {
            return null;
        }

        const json = (await res.json()) as BrandingApiResponse;
        return json.branding ?? null;
    } catch {
        return null;
    }
}
