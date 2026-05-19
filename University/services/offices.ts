import { fetchWithCache } from './utils';

export interface Office {
    id: number;
    slug: string;
    city: string;
    country: string;
    address: string;
    phone: string;
    email: string;
    type: string;
    image: string | null;
    map_url: string | null;
    description: string | null;
    hours: string | null;
}

export async function getOffices(): Promise<Office[]> {
    try {
        const data = await fetchWithCache<any>('/offices');
        // Handle plain array, paginated { data: [] }, or other wrapped shapes
        if (Array.isArray(data)) return data;
        if (data && Array.isArray(data.data)) return data.data;
        if (data && Array.isArray(data.offices)) return data.offices;
        return [];
    } catch (error) {
        console.error('Failed to fetch offices:', error);
        return [];
    }
}

export async function getOffice(slug: string): Promise<Office | null> {
    try {
        const data = await fetchWithCache<Office>(`/offices/${slug}`);
        return data;
    } catch (error) {
        console.error(`Failed to fetch office ${slug}:`, error);
        return null;
    }
}
