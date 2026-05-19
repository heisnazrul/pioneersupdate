"use client";

import { useState } from 'react';
import { Destination } from '@/lib/data/types';
import DestinationCard from '@/components/DestinationCard';

interface Labels {
    filters: {
        all: string;
        europe: string;
        na: string;
        oceania: string;
        budget: string;
    };
    searchPlaceholder: string;
    noMatchTitle: string;
    noMatchText: string;
    resetFilters: string;
    showing: string;
    destinationsWord: string;
    dir: 'ltr' | 'rtl';
    lang: 'en' | 'ar';
}

interface Props {
    initialDestinations: Destination[];
    labels: Labels;
}

// Slugs for countries in each region — always English, regardless of API lang
const EU_SLUGS = ['united-kingdom', 'uk', 'germany', 'ireland', 'hungary', 'cyprus', 'denmark', 'netherlands', 'france', 'spain', 'italy', 'sweden', 'norway', 'finland', 'austria', 'belgium', 'portugal', 'poland', 'czech-republic', 'switzerland'];
const NA_SLUGS = ['united-states', 'usa', 'canada'];
const OC_SLUGS = ['australia', 'new-zealand'];
const BUDGET_SLUGS = ['germany', 'hungary', 'cyprus', 'canada', 'poland', 'czech-republic'];

// Also match on region string (case-insensitive, partial) for API-driven filtering
const matchesRegion = (dest: Destination, keywords: string[]) => {
    const region = (dest.region || '').toLowerCase();
    return keywords.some(kw => region.includes(kw));
};

export default function DestinationsClient({ initialDestinations, labels }: Props) {
    const [search, setSearch] = useState('');
    const [activeFilter, setActiveFilter] = useState('all');
    const lang = labels.lang;

    const FILTERS = [
        { label: labels.filters.all, value: 'all' },
        { label: labels.filters.europe, value: 'eu' },
        { label: labels.filters.na, value: 'na' },
        { label: labels.filters.oceania, value: 'oc' },
        { label: labels.filters.budget, value: 'budget' },
    ];

    const filteredDestinations = initialDestinations.filter(dest => {
        const matchesSearch =
            dest.name.toLowerCase().includes(search.toLowerCase()) ||
            (dest.description || '').toLowerCase().includes(search.toLowerCase());

        let matchesFilter = true;

        switch (activeFilter) {
            case 'na':
                matchesFilter =
                    NA_SLUGS.includes(dest.slug) ||
                    matchesRegion(dest, ['north america', 'أمريكا الشمالية']);
                break;
            case 'eu':
                matchesFilter =
                    EU_SLUGS.includes(dest.slug) ||
                    matchesRegion(dest, ['europe', 'أوروبا']);
                break;
            case 'oc':
                matchesFilter =
                    OC_SLUGS.includes(dest.slug) ||
                    matchesRegion(dest, ['oceania', 'أوقيانوسيا']);
                break;
            case 'budget':
                matchesFilter =
                    BUDGET_SLUGS.includes(dest.slug) ||
                    ((dest.tuitionRange?.includes('€0') || dest.tuitionRange?.includes('€2,000') || dest.tuitionRange?.includes('€3,000')) ?? false);
                break;
            default:
                matchesFilter = true;
        }

        return matchesSearch && matchesFilter;
    });

    return (
        <div dir={labels.dir}>
            {/* Gallery Header: Filters & Search */}
            <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-12">
                {/* Filter Tabs */}
                <div className="flex flex-wrap gap-x-8 gap-y-4 items-center">
                    {FILTERS.map(filter => (
                        <button
                            key={filter.value}
                            onClick={() => setActiveFilter(filter.value)}
                            className={`text-sm font-bold uppercase tracking-wider transition-all duration-300 relative pb-2 
                                ${activeFilter === filter.value
                                    ? 'text-[#135FAE]'
                                    : 'text-slate-400 hover:text-slate-700'
                                }`
                            }
                        >
                            {filter.label}
                            <span className={`absolute bottom-0 left-0 h-0.5 bg-[#135FAE] transition-all duration-300 
                                ${activeFilter === filter.value ? 'w-full' : 'w-0'}`}>
                            </span>
                        </button>
                    ))}
                </div>

                {/* Search Input */}
                <div className="relative w-full lg:w-80 group">
                    <svg className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-[#135FAE] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        placeholder={labels.searchPlaceholder}
                        className="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-transparent rounded-full focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#135FAE]/10 focus:shadow-sm transition-all text-sm font-medium placeholder:text-slate-400"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                    />
                </div>
            </div>

            {/* Results Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">
                {filteredDestinations.map((dest) => (
                    <DestinationCard key={dest.id} destination={dest} lang={lang} />
                ))}
            </div>

            {/* Empty State */}
            {filteredDestinations.length === 0 && (
                <div className="text-center py-24 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <div className="text-5xl mb-4 opacity-50">🌍</div>
                    <h3 className="text-xl font-bold text-slate-900 mb-2">{labels.noMatchTitle}</h3>
                    <p className="text-slate-500 mb-6">{labels.noMatchText}</p>
                    <button
                        className="px-8 py-3 bg-white border border-slate-200 rounded-full shadow-sm text-sm font-bold text-slate-700 hover:text-[#135FAE] hover:border-[#135FAE] transition-all"
                        onClick={() => { setSearch(''); setActiveFilter('all'); }}
                    >
                        {labels.resetFilters}
                    </button>
                </div>
            )}

            {/* Count Indicator */}
            {filteredDestinations.length > 0 && (
                <div className="mt-8 text-center">
                    <p className="text-xs font-bold text-slate-300 uppercase tracking-widest">
                        {labels.showing} {filteredDestinations.length} {labels.destinationsWord}
                    </p>
                </div>
            )}
        </div>
    );
}
