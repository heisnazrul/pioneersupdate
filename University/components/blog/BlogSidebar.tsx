import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSearch, faChevronRight, faChevronLeft, faTag } from '@fortawesome/free-solid-svg-icons';
import { BlogPost, BlogCategory } from '@/lib/data/types';
import { extractCategories, extractTags, slugify } from '@/lib/blogs';
import { useLang } from '@/hooks/useLang';

export default function BlogSidebar({
    posts,
    categories,
}: {
    posts: BlogPost[];
    categories?: BlogCategory[];
}) {
    const { isAr } = useLang();

    // If categories not passed from API, extract from posts (though API is better)
    const derivedCategories = categories && categories.length
        ? categories
        : extractCategories(posts);

    const recentPosts = posts.slice(0, 4);
    const tags = extractTags(posts);

    return (
        <aside className="space-y-8">
            {/* Search Widget - Visual only for now or could hook up */}
            <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 className="font-bold text-lg mb-4 text-gray-900">{isAr ? 'البحث في المدونات' : 'Search Blogs'}</h3>
                <div className="relative">
                    <input
                        type="text"
                        placeholder={isAr ? 'اكتب كلمات البحث...' : 'Type keywords...'}
                        className={`w-full ${isAr ? 'pr-4 pl-10' : 'pl-4 pr-10'} py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all text-sm`}
                    />
                    <FontAwesomeIcon icon={faSearch} className={`absolute ${isAr ? 'left-4' : 'right-4'} top-1/2 transform -translate-y-1/2 text-gray-400`} />
                </div>
            </div>

            {/* Categories Widget */}
            <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 className="font-bold text-lg mb-4 text-gray-900">{isAr ? 'الفئات' : 'Categories'}</h3>
                <div className="space-y-2">
                    {derivedCategories.map((category, idx) => (
                        <Link
                            key={category.slug || idx}
                            href={`/blogs?category=${category.slug || slugify(category.name)}`}
                            className="flex items-center justify-between group p-2 hover:bg-gray-50 rounded-lg transition-colors"
                        >
                            <span className="text-gray-600 group-hover:text-blue-600 font-medium transition-colors">
                                {category.name}
                            </span>
                            <span className="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 group-hover:bg-blue-100 text-xs text-gray-500 group-hover:text-blue-600 transition-colors">
                                <FontAwesomeIcon icon={isAr ? faChevronLeft : faChevronRight} className="text-[10px]" />
                            </span>
                        </Link>
                    ))}
                </div>
            </div>

            {/* Recent Posts Widget */}
            <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 className="font-bold text-lg mb-4 text-gray-900">{isAr ? 'أحدث المقالات' : 'Recent Posts'}</h3>
                <div className="space-y-6">
                    {recentPosts.map((post, idx) => (
                        <Link key={post.id || idx} href={`/blogs/${post.slug}`} className="group block">
                            <h4 className="font-bold text-gray-800 text-sm leading-snug mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                {post.title}
                            </h4>
                            <div className="flex items-center text-xs text-gray-400 gap-3">
                                <span>{post.date}</span>
                                <span>•</span>
                                <span>{post.readTime}</span>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>

            {/* Tags Widget */}
            <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 className="font-bold text-lg mb-4 text-gray-900">{isAr ? 'مواضيع شائعة' : 'Popular Tags'}</h3>
                <div className="flex flex-wrap gap-2">
                    {tags.slice(0, 10).map((tag, idx) => (
                        <Link
                            key={idx}
                            href={`/blogs?tag=${tag.toLowerCase()}`}
                            className="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full hover:bg-blue-50 hover:text-blue-600 transition-colors"
                        >
                            <FontAwesomeIcon icon={faTag} className={`${isAr ? 'ml-1' : 'mr-1'} opacity-50`} />
                            {tag}
                        </Link>
                    ))}
                </div>
            </div>
        </aside>
    );
}
