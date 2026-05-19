"use client";

import { useEffect, useState } from 'react';
import { useParams } from 'next/navigation';
import BlogCard from '@/components/blog/BlogCard';
import BlogSidebar from '@/components/blog/BlogSidebar';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFolderOpen } from '@fortawesome/free-solid-svg-icons';
import { BlogPost } from '@/lib/data/types';
import { extractItems, normalizeBlogs } from '@/lib/blogs';

function toTitleCase(value: string) {
    return value
        .split('-')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

export default function BlogCategoryPage() {
    const params = useParams();
    const slug = params.slug as string;
    const [categoryName, setCategoryName] = useState('');
    const [filteredBlogs, setFilteredBlogs] = useState<BlogPost[]>([]);
    const [allBlogs, setAllBlogs] = useState<BlogPost[]>([]);

    useEffect(() => {
        let mounted = true;
        const load = async () => {
            try {
                const [categoryRes, listRes] = await Promise.all([
                    fetch(`/api/university/blogs/category/${slug}`, { cache: 'no-store' }),
                    fetch('/api/university/blogs', { cache: 'no-store' }),
                ]);

                const categoryJson = await categoryRes.json();
                const listJson = await listRes.json();
                const categoryItems = extractItems(categoryJson?.data?.items || categoryJson);
                const listItems = extractItems(listJson);
                const normalizedCategory = normalizeBlogs(categoryItems);
                const normalizedList = normalizeBlogs(listItems);
                const category =
                    categoryJson?.data?.category ||
                    (normalizedCategory[0] ? { name: normalizedCategory[0].category } : null);

                if (!mounted) return;
                setFilteredBlogs(normalizedCategory);
                setAllBlogs(normalizedList);
                setCategoryName(category?.name || toTitleCase(slug));
            } catch {
                if (!mounted) return;
                setFilteredBlogs([]);
                setAllBlogs([]);
                setCategoryName(toTitleCase(slug));
            }
        };
        load();
        return () => {
            mounted = false;
        };
    }, [slug]);

    return (
        <main className="bg-white min-h-screen pt-32 pb-20">

            <div className="px-4 md:px-10 xl:px-30 2xl:px-50">

                <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    {/* Main Content */}
                    <div className="lg:col-span-9">

                        {/* Header */}
                        <div className="mb-12 border-b border-gray-100 pb-8">
                            <span className="flex items-center gap-2 text-blue-600 font-bold tracking-wider uppercase text-sm mb-2">
                                <FontAwesomeIcon icon={faFolderOpen} />
                                Category
                            </span>
                            <h1 className="text-4xl md:text-5xl font-extrabold text-gray-900">
                                {categoryName}
                            </h1>
                            <p className="text-gray-500 mt-2 text-lg">
                                Browsing {filteredBlogs.length} articles in {categoryName}
                            </p>
                        </div>

                        {filteredBlogs.length > 0 ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mb-16">
                                {filteredBlogs.map((blog, idx) => (
                                    <BlogCard key={idx} blog={blog} />
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                                <h3 className="text-xl font-bold text-gray-400">No posts found in this category.</h3>
                                <p className="text-gray-500 mt-2">Try searching for something else.</p>
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
