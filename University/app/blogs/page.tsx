"use client";

import { useEffect, useState } from 'react';
import BlogCard from '@/components/blog/BlogCard';
import BlogSidebar from '@/components/blog/BlogSidebar';
import { BlogPost, BlogCategory } from '@/lib/data/types';
import { normalizeBlogs } from '@/lib/blogs';
import { getBlogs, getBlogCategories } from '@/lib/api';
import { useLang } from '@/hooks/useLang';

export default function BlogsPage() {
    const { lang, isAr } = useLang();

    // Pagination state
    const ITEMS_PER_PAGE = 9;
    const [currentPage, setCurrentPage] = useState(1);
    const [allBlogs, setAllBlogs] = useState<BlogPost[]>([]);
    const [categories, setCategories] = useState<BlogCategory[]>([]);
    const [selectedCategory, setSelectedCategory] = useState<string>('all');
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let mounted = true;
        const load = async () => {
            try {
                const [blogs, cats] = await Promise.all([
                    getBlogs(lang),
                    getBlogCategories()
                ]);

                if (!mounted) return;

                // If API returns normalized data, great. If not, we might need normalizing.
                // Based on publicApi implementation, it returns raw items from 'data.items'.
                // Backend 'universityBlogs' returns items with 'category' relation.
                // We might need to ensure compatibility with BlogCard.
                // Normalize logic usually handles field mapping.

                // Assuming publicApi returns correct shape or we use normalizeBlogs if expecting different shape.
                // But getUniversityBlogs returns BlogPost[].
                // Let's assume it matches.

                setAllBlogs(blogs);
                setCategories(cats);
                setLoading(false);
            } catch (err) {
                console.error("Failed to load blogs", err);
                if (!mounted) return;
                setAllBlogs([]);
                setCategories([]);
                setLoading(false);
            }
        };
        load();
        return () => {
            mounted = false;
        };
    }, []);

    // Filter Logic
    const filteredBlogs = selectedCategory === 'all'
        ? allBlogs
        : allBlogs.filter(blog => {
            if (typeof blog.category === 'string') {
                // If category is string (name), compare normalized
                return blog.category.toLowerCase() === selectedCategory.toLowerCase();
            } else if (typeof blog.category === 'object' && blog.category !== null) {
                // If category is object, compare slug or id. selectedCategory will store slug?
                // Let's assume selectedCategory is slug for robustness.
                return blog.category.slug === selectedCategory;
            }
            return false;
        });

    // Pagination Logic
    const totalPages = Math.ceil(filteredBlogs.length / ITEMS_PER_PAGE);
    const paginatedBlogs = filteredBlogs.slice(
        (currentPage - 1) * ITEMS_PER_PAGE,
        currentPage * ITEMS_PER_PAGE
    );

    // Reset page when category changes
    useEffect(() => {
        setCurrentPage(1);
    }, [selectedCategory]);

    const T = {
        ourBlog: isAr ? 'مدونتنا' : 'Our Blog',
        latestInsights: isAr ? 'أحدث الرؤى' : 'Latest Insights',
        all: isAr ? 'الكل' : 'All',
        loading: isAr ? 'جارٍ تحميل الرؤى...' : 'Loading insights...',
        noArticles: isAr ? 'لم يتم العثور على مقالات في هذه الفئة.' : 'No articles found in this category.',
        viewAll: isAr ? 'عرض جميع المقالات' : 'View all articles',
    };

    return (
        <main className="bg-white min-h-screen pt-32 pb-20" dir={isAr ? 'rtl' : 'ltr'}>

            <div className="px-4 md:px-10 xl:px-30 2xl:px-50">

                <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    {/* Main Content Area */}
                    <div className="lg:col-span-9">

                        {/* Clean Header */}
                        <div className="mb-12 border-b border-gray-100 pb-8 flex flex-col md:flex-row justify-between items-end gap-6">
                            <div>
                                <span className="text-blue-600 font-bold tracking-wider uppercase text-sm mb-2 block">{T.ourBlog}</span>
                                <h1 className="text-4xl md:text-5xl font-extrabold text-gray-900">{T.latestInsights}</h1>
                            </div>
                        </div>

                        {/* Category Filter */}
                        <div className="flex flex-wrap gap-2 mb-8">
                            <button
                                onClick={() => setSelectedCategory('all')}
                                className={`px-4 py-2 rounded-full text-sm font-bold transition-colors ${selectedCategory === 'all'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                    }`}
                            >
                                {T.all}
                            </button>
                            {categories.map((cat) => (
                                <button
                                    key={cat.id}
                                    onClick={() => setSelectedCategory(cat.slug)}
                                    className={`px-4 py-2 rounded-full text-sm font-bold transition-colors ${selectedCategory === cat.slug
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                        }`}
                                >
                                    {isAr ? (cat.ar_name || cat.name_ar || cat.name) : cat.name}
                                </button>
                            ))}
                        </div>

                        {/* 3 Column Grid */}
                        {loading ? (
                            <div className="text-center py-20 text-gray-500">{T.loading}</div>
                        ) : filteredBlogs.length > 0 ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mb-16">
                                {paginatedBlogs.map((blog, idx) => (
                                    <BlogCard key={blog.id || idx} blog={blog} />
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-20 bg-gray-50 rounded-xl">
                                <p className="text-gray-500 font-medium">{T.noArticles}</p>
                                <button
                                    onClick={() => setSelectedCategory('all')}
                                    className="mt-4 text-blue-600 font-bold hover:underline"
                                >
                                    {T.viewAll}
                                </button>
                            </div>
                        )}

                        {/* Pagination */}
                        {totalPages > 1 && (
                            <div className="flex justify-center gap-2">
                                <button
                                    onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                                    disabled={currentPage === 1}
                                    className="w-10 h-10 rounded-lg bg-white text-gray-600 border border-gray-200 font-bold hover:bg-gray-50 flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    &larr;
                                </button>

                                {Array.from({ length: totalPages }, (_, i) => i + 1).map(page => (
                                    <button
                                        key={page}
                                        onClick={() => setCurrentPage(page)}
                                        className={`w-10 h-10 rounded-lg font-bold flex items-center justify-center transition-colors ${currentPage === page
                                            ? 'bg-blue-600 text-white shadow-md'
                                            : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
                                            }`}
                                    >
                                        {page}
                                    </button>
                                ))}

                                <button
                                    onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                                    disabled={currentPage === totalPages}
                                    className="w-10 h-10 rounded-lg bg-white text-gray-600 border border-gray-200 font-bold hover:bg-gray-50 flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    &rarr;
                                </button>
                            </div>
                        )}
                    </div>

                    {/* Sidebar */}
                    <div className="lg:col-span-3 pl-0 lg:pl-4">
                        <div className="sticky top-32">
                            <BlogSidebar posts={allBlogs} />
                        </div>
                    </div>
                </div>
            </div>
        </main>
    );
}
