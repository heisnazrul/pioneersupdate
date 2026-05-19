"use client";

import { useEffect, useState } from 'react';
import { useParams } from 'next/navigation';
import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faClock, faUser, faCalendar } from '@fortawesome/free-solid-svg-icons';
import BlogSidebar from '@/components/blog/BlogSidebar';
import { BlogPost } from '@/lib/data/types';
import { formatContentHtml, normalizeBlog, normalizeBlogs } from '@/lib/blogs';
import { getBlogBySlug, getBlogs } from '@/lib/api';
import { useLang } from '@/hooks/useLang';

export default function BlogPostPage() {
    const params = useParams();
    const { lang, isAr } = useLang();
    const slug = params.slug as string;
    const [blog, setBlog] = useState<BlogPost | null>(null);
    const [allBlogs, setAllBlogs] = useState<BlogPost[]>([]);
    const [loaded, setLoaded] = useState(false);

    useEffect(() => {
        let mounted = true;
        const load = async () => {
            try {
                const [blogData, allBlogsData] = await Promise.all([
                    getBlogBySlug(slug, lang),
                    getBlogs(lang),
                ]);

                if (!mounted) return;

                // publicApi methods return normalized or raw data.
                // If getUniversityBlogBySlug returns BlogPost, we can use it.
                // Assuming it returns correct shape or we normalize here if needed.
                // But getUniversityBlogBySlug returns BlogPost or undefined.

                // If we need normalization:
                // normalizeBlog(blogData) if blogData is raw.
                // But publicApi usually returns raw from API.
                // Let's check publicApi implementation again.
                // It returns "data?.data || data".
                // If backend returns keys like "created_at", we might need normalization.
                // BlogPost type has "date", "readTime". Backend might not have these computed.

                // Implementation in BlogsPage used:
                // const normalized = normalizeBlogs(items);
                // So I probably need to normalize the result from publicApi too if publicApi returns raw backend data.

                // Let's wrap raw data with normalization.
                setBlog(blogData ? normalizeBlog(blogData, lang) : null);

                // For list
                // publicApi returns array.
                // We normalize it.
                setAllBlogs(normalizeBlogs(allBlogsData, lang));

                setLoaded(true);
            } catch {
                if (!mounted) return;
                setBlog(null);
                setAllBlogs([]);
                setLoaded(true);
            }
        };
        load();
        return () => {
            mounted = false;
        };
    }, [slug]);

    const T = {
        notFound: isAr ? 'لم يتم العثور على المقال' : 'Post Not Found',
        back: isAr ? '← العودة إلى المدونات' : '← Back to Blogs',
        home: isAr ? 'الرئيسية' : 'Home',
        blogs: isAr ? 'المدونات' : 'Blogs',
        expert: isAr ? 'خبير الدراسة في الخارج' : 'Study Abroad Expert',
        relatedTopics: isAr ? 'مواضيع ذات صلة' : 'Related Topics'
    };

    if (loaded && !blog) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-white" dir={isAr ? 'rtl' : 'ltr'}>
                <div className="text-center">
                    <h1 className="text-4xl font-bold text-gray-900 mb-4">{T.notFound}</h1>
                    <Link href="/blogs" className="text-blue-600 font-bold hover:underline">
                        {T.back}
                    </Link>
                </div>
            </div>
        );
    }

    if (!blog) return null;

    const contentHtml = formatContentHtml(blog.content || "");
    const tags = Array.isArray(blog.tags) ? blog.tags : [];

    return (
        <main className="bg-white min-h-screen pt-32 pb-20" dir={isAr ? 'rtl' : 'ltr'}>
            <div className="px-4 md:px-10 xl:px-30 2xl:px-50">

                {/* Breadcrumb */}
                <div className="mb-8 flex items-center gap-2 text-sm text-gray-500">
                    <Link href="/" className="hover:text-blue-600">{T.home}</Link>
                    <span>/</span>
                    <Link href="/blogs" className="hover:text-blue-600">{T.blogs}</Link>
                    <span>/</span>
                    <span className="text-gray-900 font-medium truncate max-w-[200px]">{blog.title}</span>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    {/* Main Content */}
                    <article className="lg:col-span-9">

                        {/* Header */}
                        <div className="mb-8">
                            <div className="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6">
                                <span className="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                    {typeof blog.category === 'object' ? blog.category.name : blog.category}
                                </span>
                                <span className="flex items-center gap-1">
                                    <FontAwesomeIcon icon={faCalendar} className="text-gray-400" />
                                    {blog.date}
                                </span>
                                <span className="flex items-center gap-1">
                                    <FontAwesomeIcon icon={faClock} className="text-gray-400" />
                                    {blog.readTime}
                                </span>
                            </div>

                            <h1 className="text-3xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-8">
                                {blog.title}
                            </h1>

                            {/* Author */}
                            <div className="flex items-center gap-4 pb-8 border-b border-gray-100">
                                <div className="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden">
                                    <FontAwesomeIcon icon={faUser} className="text-gray-400 text-xl" />
                                </div>
                                <div>
                                    <p className="font-bold text-gray-900 text-sm">{blog.author}</p>
                                    <p className="text-xs text-gray-500">{T.expert}</p>
                                </div>
                            </div>
                        </div>

                        {/* Featured Image */}
                        <div className="relative w-full h-[300px] md:h-[500px] rounded-3xl overflow-hidden mb-12 shadow-sm">
                            <img
                                src={blog.imageUrl}
                                alt={blog.title}
                                className="h-full w-full object-cover"
                                loading="lazy"
                            />
                        </div>

                        {/* Content Body */}
                        <div className="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            <p className="lead text-2xl text-gray-900 font-medium mb-8 leading-snug">
                                {blog.excerpt}
                            </p>
                            {contentHtml ? (
                                <div dangerouslySetInnerHTML={{ __html: contentHtml }} />
                            ) : null}
                        </div>

                        {/* Tags */}
                        <div className="mt-16 pt-10 border-t border-gray-100">
                            <h4 className="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">{T.relatedTopics}</h4>
                            <div className="flex flex-wrap gap-2">
                                {tags.map((tag, idx) => (
                                    <span key={idx} className="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-200 transition-colors cursor-pointer">
                                        #{tag}
                                    </span>
                                ))}
                            </div>
                        </div>

                    </article>

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
