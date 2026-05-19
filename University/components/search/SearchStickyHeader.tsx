"use client";

import { useSearchParams, useRouter, usePathname } from 'next/navigation';
import { useState, useEffect } from 'react';
import { Country } from '@/lib/data/types';
import { getPopularCountries } from '@/services/publicData';
import { motion } from 'framer-motion';
import { useLang } from '@/hooks/useLang';

interface SearchStickyHeaderProps {
    count: number;
    type: 'courses' | 'universities';
}

export default function SearchStickyHeader({ count, type }: SearchStickyHeaderProps) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const pathname = usePathname();
    const [keyword, setKeyword] = useState(searchParams.get('keyword') || '');
    const [popularCountries, setPopularCountries] = useState<Country[]>([]);
    const { isAr } = useLang();

    useEffect(() => {
        getPopularCountries(5).then(setPopularCountries);
    }, []);

    // Sync local state with URL params (e.g. on Clear All or Navigation)
    useEffect(() => {
        setKeyword(searchParams.get('keyword') || '');
    }, [searchParams]);

    const updateSearch = (key: string, value: string) => {
        const params = new URLSearchParams(searchParams.toString());
        if (value) params.set(key, value);
        else params.delete(key);
        params.delete('page'); // Reset pagination
        router.push(`${pathname}?${params.toString()}`, { scroll: false });
    };

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        updateSearch('keyword', keyword);
    };

    return (
        <div className="sticky top-0 z-40 bg-white border-b border-gray-100 shadow-sm py-4">
            <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                <div className="flex flex-col lg:flex-row items-center gap-6 justify-between">

                    {/* Left: Search & Country Quick Select */}
                    <div className="flex flex-1 items-center gap-4 w-full">
                        <form onSubmit={handleSearch} className="relative flex-1 max-w-md">
                            <input
                                type="text"
                                placeholder={isAr ? `ابحث في ${type === 'courses' ? 'الدورات' : 'الجامعات'}...` : `Search ${type}...`}
                                value={keyword}
                                onChange={(e) => setKeyword(e.target.value)}
                                className="w-full bg-gray-50 border border-gray-100 rounded-xl px-10 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none"
                            />
                            <svg className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </form>

                        <div className="hidden md:flex items-center gap-2">
                            <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mr-2">{isAr ? 'اختيار سريع:' : 'Quick Select:'}</span>
                            {popularCountries.map(c => (
                                <button
                                    key={c.id}
                                    onClick={() => updateSearch('destination', c.name)}
                                    className={`px-3 py-1.5 rounded-full text-xs font-semibold transition-all border ${searchParams.get('destination') === c.name
                                        ? 'bg-primary text-white border-primary shadow-sm'
                                        : 'bg-white border-gray-200 text-gray-600 hover:border-primary hover:text-primary'
                                        }`}
                                >
                                    {c.name}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Right: Count & Sort */}
                    <div className="flex items-center gap-6 shrink-0">
                        <div className="text-sm font-medium text-gray-500">
                            {isAr ? 'عرض ' : 'Showing '}<span className="text-primary font-bold">{count}</span>{isAr ? ` ${type === 'courses' ? 'دورة' : 'جامعة'}` : ` ${type}`}
                        </div>

                        <div className="flex items-center gap-2">
                            <label className="text-xs font-bold text-gray-400 uppercase tracking-widest">{isAr ? 'ترتيب:' : 'Sort:'}</label>
                            <select
                                value={searchParams.get('sort') || ''}
                                onChange={(e) => updateSearch('sort', e.target.value)}
                                className="bg-transparent text-sm font-bold text-gray-800 outline-none cursor-pointer border-b-2 border-transparent hover:border-primary transition-all pb-0.5"
                            >
                                <option value="">{isAr ? 'الأكثر صلة' : 'Relevance'}</option>
                                {type === 'courses' ? (
                                    <>
                                        <option value="tuition_asc">{isAr ? 'أقل رسوم دراسية' : 'Lowest Tuition'}</option>
                                        <option value="tuition_desc">{isAr ? 'أعلى رسوم دراسية' : 'Highest Tuition'}</option>
                                        <option value="duration_asc">{isAr ? 'أقصر مدة' : 'Shortest Duration'}</option>
                                    </>
                                ) : (
                                    <>
                                        <option value="rank_asc">{isAr ? 'الأعلى تصنيفاً' : 'Top Ranked'}</option>
                                        <option value="rank_desc">{isAr ? 'الأدنى تصنيفاً' : 'Lowest Ranked'}</option>
                                    </>
                                )}
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    );
}
