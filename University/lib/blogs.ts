import { BlogPost } from "./data/types";

const API_BASE = process.env.NEXT_PUBLIC_BACKEND_URL || "https://backend.pioneersedu.com";

export function slugify(value: string): string {
    return (value || "").toString().toLowerCase().trim().replace(/\s+/g, "-");
}

export function stripHtml(value: string): string {
    return (value || "").replace(/<[^>]*>/g, "");
}

export function summarize(value: string, limit = 180): string {
    const clean = stripHtml(value || "");
    if (clean.length <= limit) return clean;
    return `${clean.slice(0, limit)}...`;
}

export function formatDate(value?: string): string {
    if (!value) return "";
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return value;
    return date.toLocaleDateString("en-US", {
        month: "long",
        day: "2-digit",
        year: "numeric",
    });
}

export function estimateReadTime(value: string): string {
    const words = stripHtml(value || "").trim().split(/\s+/).filter(Boolean).length;
    if (!words) return "";
    const minutes = Math.max(1, Math.round(words / 200));
    return `${minutes} min read`;
}

// Backend sends full URL mostly, but just in case
export function resolveImage(item: any): string {
    const raw =
        item?.imageUrl ||
        item?.image ||
        item?.featured_image ||
        "";

    if (!raw) return "/assets/blogs/study.png"; // Fallback

    // Fix: If it's already a full URL (from backend), just return it.
    // Also handle double slashes if backend returns them incorrectly.
    if (raw.startsWith("http")) {
        return raw.replace(/([^:]\/)\/+/g, "$1");
    }

    // Otherwise, append to API_BASE
    return `${API_BASE}/storage/${raw.replace(/^\/+/, "")}`;
}

export function normalizeBlog(item: any, lang?: string): BlogPost {
    const isAr = lang === 'ar';

    const rawTitle = isAr ? (item?.ar_title || item?.title) : item?.title;
    const title = rawTitle || "Blog post";

    const slug = item?.slug || slugify(title);

    const rawExcerptData = isAr
        ? (item?.ar_summary || item?.ar_excerpt || item?.summary || item?.excerpt || "")
        : (item?.summary || item?.excerpt || "");

    const contentData = isAr ? (item?.ar_content || item?.content || "") : (item?.content || "");

    const excerpt = rawExcerptData || summarize(contentData);

    // Category can be object or string in API
    let categoryRaw = item?.category;
    if (isAr && categoryRaw && typeof categoryRaw === 'object') {
        categoryRaw = { ...categoryRaw, name: categoryRaw.ar_name || categoryRaw.name_ar || categoryRaw.name };
    } else if (isAr && typeof categoryRaw === 'string') {
        // We can't easily translate a raw string without object data, but it's handled if it's an object.
    }

    const imageUrl = resolveImage(item);
    const author = isAr ? (item?.ar_author || item?.author || item?.publisher || "مسؤول") : (item?.author || item?.publisher || "Admin");
    const date = item?.published_at || item?.date || "";

    // backend tags might be array of strings or objects {name:string, ar_name:string}
    const tags = Array.isArray(item?.tags) ? item.tags.map((t: any) => {
        if (typeof t === 'string') return t;
        return isAr ? (t.ar_name || t.name_ar || t.name) : t.name;
    }) : [];

    const readTime = item?.readTime || estimateReadTime(contentData || excerpt) || (isAr ? "5 دقائق قراءة" : "5 min read");

    return {
        id: String(item?.id ?? slug),
        slug: String(slug),
        title: String(title),
        excerpt: String(excerpt),
        content: String(contentData),
        author: String(author),
        date: String(date),
        category: categoryRaw,
        imageUrl: imageUrl,
        tags,
        readTime,
    };
}

export function normalizeBlogs(items: any[], lang?: string): BlogPost[] {
    return items.map(item => normalizeBlog(item, lang));
}

export function extractCategories(items: BlogPost[]): Array<{ name: string; slug: string }> {
    const map = new Map<string, string>();
    items.forEach((item) => {
        let name = "Blog";
        if (typeof item.category === 'string') {
            name = item.category;
        } else if (typeof item.category === 'object' && item.category?.name) {
            name = item.category.name;
        }
        const slug = typeof item.category === 'object' && item.category?.slug ? item.category.slug : slugify(name);
        if (!map.has(slug)) map.set(slug, name);
    });
    return Array.from(map.entries()).map(([slug, name]) => ({ slug, name }));
}

export function extractTags(items: BlogPost[]): string[] {
    const set = new Set<string>();
    items.forEach((item) => {
        (item.tags || []).forEach((tag) => set.add(tag));
    });
    return Array.from(set.values());
}

export function escapeHtml(value: string): string {
    return (value || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/\"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

export function formatContentHtml(content: string): string {
    const trimmed = (content || "").trim();
    if (!trimmed) return "";
    const looksLikeHtml = /<[a-z][\s\S]*>/i.test(trimmed);
    if (looksLikeHtml) return trimmed;
    // If markdown-ish or plain text, wrap in p
    return trimmed.split(/\n\s*\n/).map(p => `<p>${escapeHtml(p)}</p>`).join("");
}

export function extractItems(data: any): any[] {
    if (!data) return [];
    if (Array.isArray(data)) return data;
    if (Array.isArray(data.data)) return data.data;
    if (Array.isArray(data.items)) return data.items;
    return [];
}
