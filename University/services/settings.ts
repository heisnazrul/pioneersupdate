import { fetchWithCache } from './utils';

export interface SocialLink {
    platform: string;
    url: string;
}

export interface ContactInfo {
    site_name: string;
    site_email: string;
    site_phone: string;
    site_address: string;
    contact_description: string;
    social_links: SocialLink[];
}

export interface Branding {
    app_name: string;
    logo_url: string;
    favicon_url: string;
    primary_color: string;
}

export interface PublicSettings {
    contact: ContactInfo;
    branding: Branding;
}

export async function getPublicSettings(): Promise<PublicSettings> {
    try {
        const data = await fetchWithCache<PublicSettings>(`/settings/public`);
        return data;
    } catch (error) {
        console.error('Failed to fetch public settings:', error);
        // Fallback default
        return {
            contact: {
                site_name: 'Pioneers Admissions',
                site_email: 'info@pioneers.edu.sa',
                site_phone: '+966 50 123 4567',
                site_address: '',
                contact_description: '',
                social_links: []
            },
            branding: {
                app_name: 'Pioneers Admissions',
                logo_url: '',
                favicon_url: '',
                primary_color: '#000000'
            }
        };
    }
}
