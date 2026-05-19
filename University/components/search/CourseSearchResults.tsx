"use client";

import { useSearchParams, useRouter, usePathname } from 'next/navigation';
import { Course } from '@/lib/data/types';
import CourseCard from '@/components/search/CourseCard';
import SearchStickyHeader from '@/components/search/SearchStickyHeader';
import SearchSidebar from '@/components/search/SearchSidebar';
import ActiveFilterChips from '@/components/search/ActiveFilterChips';
import { motion, AnimatePresence } from 'framer-motion';

interface CourseSearchResultsProps {
    initialCourses: Course[];
    total: number;
    currentPage: number;
    pageSize: number;
}

export default function CourseSearchResults({ initialCourses, total, currentPage, pageSize }: CourseSearchResultsProps) {
    const searchParams = useSearchParams();
    const router = useRouter();
    const pathname = usePathname();

    const totalPages = Math.ceil(total / pageSize);
    const isEmpty = initialCourses.length === 0;

    const handlePageChange = (newPage: number) => {
        const params = new URLSearchParams(searchParams.toString());
        params.set('page', newPage.toString());
        router.push(`${pathname}?${params.toString()}`);
    };

    return (
        <div className="min-h-screen bg-[#F9FAFB]">
            {/* Stick Header */}
            <SearchStickyHeader count={total} type="courses" />

            <div className="px-4 md:px-10 xl:px-30 2xl:px-50 py-8">
                <div className="flex flex-col lg:flex-row gap-8">

                    {/* Sidebar */}
                    <SearchSidebar type="courses" />

                    {/* Results Area */}
                    <div className="flex-1 min-w-0">
                        <ActiveFilterChips />

                        {!isEmpty ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                <AnimatePresence mode="popLayout">
                                    {initialCourses.map((course) => (
                                        <CourseCard key={course.id} course={course} />
                                    ))}
                                </AnimatePresence>
                            </div>
                        ) : (
                            <div className="text-center py-24 bg-white rounded-3xl border border-gray-100 shadow-sm">
                                <div className="text-5xl mb-6">🔍</div>
                                <h3 className="text-xl font-bold text-gray-900 mb-2">No courses found</h3>
                                <p className="text-gray-500 max-w-xs mx-auto">We couldn't find any courses matching your current filters. Try broadening your search.</p>
                                <button
                                    onClick={() => router.push(pathname)}
                                    className="mt-8 text-primary font-bold text-sm hover:underline p-2"
                                >
                                    Clear All Filters
                                </button>
                            </div>
                        )}

                        {/* Pagination */}
                        {totalPages > 1 && (
                            <div className="mt-12 flex justify-center items-center gap-3">
                                <button
                                    disabled={currentPage <= 1}
                                    onClick={() => handlePageChange(currentPage - 1)}
                                    className="p-2 bg-white border border-gray-100 text-gray-400 hover:text-primary hover:border-primary disabled:opacity-30 disabled:cursor-not-allowed rounded-lg transition-all"
                                >
                                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" /></svg>
                                </button>

                                <div className="flex items-center gap-2">
                                    {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
                                        let p = i + 1;
                                        if (totalPages > 5) {
                                            if (currentPage > 3) p = currentPage - 2 + i;
                                            if (p > totalPages) p = totalPages - (4 - i);
                                        }

                                        return (
                                            <button
                                                key={p}
                                                onClick={() => handlePageChange(p)}
                                                className={`w-10 h-10 rounded-lg text-sm font-bold transition-all ${currentPage === p
                                                    ? 'bg-primary text-white shadow-md'
                                                    : 'bg-white border border-gray-100 text-gray-500 hover:bg-gray-50'
                                                    }`}
                                            >
                                                {p}
                                            </button>
                                        );
                                    })}
                                </div>

                                <button
                                    disabled={currentPage >= totalPages}
                                    onClick={() => handlePageChange(currentPage + 1)}
                                    className="p-2 bg-white border border-gray-100 text-gray-400 hover:text-primary hover:border-primary disabled:opacity-30 disabled:cursor-not-allowed rounded-lg transition-all"
                                >
                                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg>
                                </button>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
