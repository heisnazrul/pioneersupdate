import { BlogCategory, BlogPost } from "@/lib/data/types";
import { normalizeBlogs, normalizeBlog } from "@/lib/blogs";

const BACKEND_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000';

export async function fetchBlogs(params?: { category?: string; search?: string; page?: number }): Promise<{ items: BlogPost[], meta?: any }> {
    try {
        const url = new URL(`${BACKEND_URL}/api/blogs`);
        if (params?.category && params.category !== 'all') url.searchParams.append('category', params.category);
        if (params?.search) url.searchParams.append('search', params.search);
        if (params?.page) url.searchParams.append('page', params.page.toString());

        const res = await fetch(url.toString(), { next: { revalidate: 60 } });
        if (!res.ok) throw new Error("Failed to fetch blogs");

        const json = await res.json();
        const data = json.data || [];
        const meta = json.meta || {};

        return {
            items: normalizeBlogs(Array.isArray(data) ? data : []),
            meta
        };
    } catch (e) {
        console.error(e);
        return { items: [] };
    }
}

export async function fetchBlogCategories(): Promise<BlogCategory[]> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/blog-categories`, { next: { revalidate: 3600 } });
        if (!res.ok) throw new Error("Failed to fetch categories");
        const data = await res.json();
        return Array.isArray(data) ? data : [];
    } catch (e) {
        console.error(e);
        return [];
    }
}

export async function fetchBlogBySlug(slug: string): Promise<BlogPost | null> {
    try {
        const res = await fetch(`${BACKEND_URL}/api/blogs/${slug}`, { next: { revalidate: 60 } });
        if (!res.ok) return null;
        const data = await res.json();
        return normalizeBlog(data);
    } catch (e) {
        console.error(e);
        return null;
    }
}
