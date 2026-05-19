import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faClock, faUser, faArrowRight, faArrowLeft } from '@fortawesome/free-solid-svg-icons';
import { BlogPost } from '@/lib/data/types';
import Image from 'next/image';
import { useLang } from '@/hooks/useLang';

interface BlogCardProps {
    blog: BlogPost;
}

export default function BlogCard({ blog }: BlogCardProps) {
    const { isAr } = useLang();

    // Determine category display name
    const catName = typeof blog.category === 'object' ? blog.category.name : blog.category;

    return (
        <article className="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full group">
            {/* Image Container */}
            <Link href={`/blogs/${blog.slug}`} className="relative h-60 overflow-hidden block">
                {/* Using standard img for now to match old codebase style or mixed sources */}
                <div className="relative w-full h-full">
                    <Image
                        src={blog.imageUrl || '/assets/blogs/study.png'}
                        alt={blog.title}
                        fill
                        className="object-cover group-hover:scale-105 transition-transform duration-700"
                        unoptimized
                    />
                </div>

                <div className={`absolute top-4 ${isAr ? 'right-4' : 'left-4'} bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-blue-600 uppercase tracking-wide shadow-sm`}>
                    {catName}
                </div>
            </Link>

            {/* Content */}
            <div className="p-6 flex-1 flex flex-col">
                <div className="flex items-center text-xs text-gray-400 mb-3 gap-4">
                    <span className="flex items-center gap-1">
                        <FontAwesomeIcon icon={faUser} className="text-gray-300" />
                        {blog.author}
                    </span>
                    <span className="flex items-center gap-1">
                        <FontAwesomeIcon icon={faClock} className="text-gray-300" />
                        {blog.readTime || (isAr ? '5 دقائق قراءة' : '5 min read')}
                    </span>
                </div>

                <Link href={`/blogs/${blog.slug}`} className="block mb-3">
                    <h3 className="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                        {blog.title}
                    </h3>
                </Link>

                <p className="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-3 flex-1">
                    {blog.excerpt}
                </p>

                <div className="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                    <span className="text-xs text-gray-400 font-medium">{blog.date}</span>
                    {isAr ? (
                        <Link href={`/blogs/${blog.slug}`} className="text-sm font-bold text-blue-600 flex items-center gap-2 group-hover:-translate-x-1 transition-transform">
                            <FontAwesomeIcon icon={faArrowLeft} className="text-xs" />
                            اقرأ المقال
                        </Link>
                    ) : (
                        <Link href={`/blogs/${blog.slug}`} className="text-sm font-bold text-blue-600 flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                            Read Article
                            <FontAwesomeIcon icon={faArrowRight} className="text-xs" />
                        </Link>
                    )}
                </div>
            </div>
        </article>
    );
}
