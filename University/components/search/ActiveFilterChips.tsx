"use client";

import { useSearchParams, useRouter, usePathname } from 'next/navigation';

export default function ActiveFilterChips() {
    const router = useRouter();
    const searchParams = useSearchParams();
    const pathname = usePathname();

    const filters = Array.from(searchParams.entries()).filter(([key]) =>
        !['page', 'sort', 'keyword'].includes(key)
    );

    if (filters.length === 0) return null;

    const removeFilter = (key: string) => {
        const params = new URLSearchParams(searchParams.toString());
        params.delete(key);
        params.delete('page');
        router.push(`${pathname}?${params.toString()}`, { scroll: false });
    };

    const clearAll = () => {
        const params = new URLSearchParams(searchParams.toString());
        const keyword = params.get('keyword');
        const sort = params.get('sort');

        // Use a fresh object to clear everything but keep core context
        const newParams = new URLSearchParams();
        if (keyword) newParams.set('keyword', keyword);
        if (sort) newParams.set('sort', sort);

        router.push(`${pathname}?${newParams.toString()}`, { scroll: false });
    };

    const formatLabel = (key: string, value: string) => {
        const labels: Record<string, string> = {
            destination: 'Country',
            city: 'City',
            level: 'Level',
            intake: 'Intake',
            tuitionMax: 'Budget',
            rankingMax: 'Rank',
            hasScholarship: 'Scholarship'
        };
        return `${labels[key] || key}: ${value === 'true' ? 'Yes' : value}`;
    };

    return (
        <div className="flex flex-wrap items-center gap-2 mb-6">
            <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mr-1">Active Filters:</span>
            {filters.map(([key, value]) => (
                <button
                    key={key}
                    onClick={() => removeFilter(key)}
                    className="group flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 hover:border-primary rounded-full text-xs font-semibold text-gray-600 hover:text-primary transition-all shadow-sm"
                >
                    {formatLabel(key, value)}
                    <svg className="w-3 h-3 text-gray-400 group-hover:text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            ))}
            <button
                onClick={clearAll}
                className="text-xs font-bold text-red-500 hover:text-red-600 px-2 py-1 transition-colors"
            >
                Clear All
            </button>
        </div>
    );
}
