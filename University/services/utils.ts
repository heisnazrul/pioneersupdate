export const API_URL = process.env.NEXT_PUBLIC_BACKEND_URL ? `${process.env.NEXT_PUBLIC_BACKEND_URL}/api` : 'http://localhost:8000/api';

export async function fetchWithCache<T>(endpoint: string, cache: RequestCache = 'no-store'): Promise<T> {
    const res = await fetch(`${API_URL}${endpoint}`, { cache });
    if (!res.ok) {
        throw new Error(`Failed to fetch ${endpoint}: ${res.statusText}`);
    }
    return await res.json();
}
