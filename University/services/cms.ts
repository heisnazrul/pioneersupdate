const API_URL = process.env.NEXT_PUBLIC_BACKEND_URL ? `${process.env.NEXT_PUBLIC_BACKEND_URL}/api` : 'http://localhost:8000/api';

export interface CmsPageData {
    id: number;
    slug: string;
    title: string;
    sub_title: string | null;
    meta_title: string | null;
    meta_description: string | null;
    content: any; // Using any for flexibility as content structure varies per page
}

export async function getCmsPage(slug: string, lang?: string): Promise<CmsPageData | null> {
    try {
        const query = lang ? `?lang=${encodeURIComponent(lang)}` : '';
        const response = await fetch(`${API_URL}/cms-pages/${slug}${query}`, {
            cache: 'no-store', // Always fetch fresh data
        });

        if (!response.ok) {
            console.error(`Failed to fetch CMS page: ${slug}`, response.statusText);
            return null;
        }

        const json = await response.json();
        return json.data;
    } catch (error) {
        console.error(`Error fetching CMS page: ${slug}`, error);
        return null;
    }
}
