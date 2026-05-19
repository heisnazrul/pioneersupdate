"use client";

import { useState, useEffect } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import { getPopularCountries } from '@/services/publicData';
import { Country } from '@/lib/data/types';

interface AdvancedFilterBarProps {
    type: 'courses' | 'universities';
}

export default function AdvancedFilterBar({ type }: AdvancedFilterBarProps) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const [isSticky, setIsSticky] = useState(false);
    const [isExpanded, setIsExpanded] = useState(false);
    const [countries, setCountries] = useState<Country[]>([]);

    useEffect(() => {
        getPopularCountries(100).then(setCountries);
    }, []);

    // Filter States
    const [filters, setFilters] = useState({
        destination: searchParams.get('destination') || '',
        city: searchParams.get('city') || '',
        level: searchParams.get('level') || '',
        intake: searchParams.get('intake') || '',
        ielts: searchParams.get('ielts') || '',
        sort: searchParams.get('sort') || 'relevance'
    });

    // Scroll Handler
    useEffect(() => {
        const handleScroll = () => {
            setIsSticky(window.scrollY > 100);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    // Sync with URL params
    useEffect(() => {
        setFilters(prev => ({
            ...prev,
            destination: searchParams.get('destination') || '',
            city: searchParams.get('city') || '',
            level: searchParams.get('level') || '',
            intake: searchParams.get('intake') || '',
            ielts: searchParams.get('ielts') || '',
            sort: searchParams.get('sort') || 'relevance'
        }));
    }, [searchParams]);

    const handleFilterChange = (key: string, value: string) => {
        const newFilters = { ...filters, [key]: value };
        setFilters(newFilters);
        applyFilters(newFilters);
    };

    const applyFilters = (currentFilters: typeof filters) => {
        const params = new URLSearchParams(searchParams.toString());

        // Remove 'page' on filter change to reset to 1
        params.delete('page');

        Object.entries(currentFilters).forEach(([key, value]) => {
            if (value) params.set(key, value);
            else params.delete(key);
        });

        router.push(`?${params.toString()}`, { scroll: false });
    };

    return (
        <div className={`transition-all duration-300 z-40 bg-white border-b border-gray-200 sticky top-0 md:top-[72px] ${isSticky ? 'shadow-md py-2' : 'py-4'}`}>
            <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                <div className="flex flex-col gap-4">

                    {/* Primary Row: Essential Filters + Sort */}
                    <div className="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div className="flex gap-2 w-full md:w-auto overflow-x-auto no-scrollbar pb-2 md:pb-0">
                            {/* Destination */}
                            <select
                                value={filters.destination}
                                onChange={(e) => handleFilterChange('destination', e.target.value)}
                                className="filter-select"
                            >
                                <option value="">All Countries</option>
                                {countries.map(c => (
                                    <option key={c.id} value={c.name}>{c.name}</option>
                                ))}
                            </select>

                            {/* Level (Courses only) */}
                            {type === 'courses' && (
                                <select
                                    value={filters.level}
                                    onChange={(e) => handleFilterChange('level', e.target.value)}
                                    className="filter-select"
                                >
                                    <option value="">All Levels</option>
                                    <option value="Bachelor">Bachelor</option>
                                    <option value="Master">Master</option>
                                    <option value="Diploma">Diploma</option>
                                </select>
                            )}

                            {/* Intake */}
                            <select
                                value={filters.intake}
                                onChange={(e) => handleFilterChange('intake', e.target.value)}
                                className="filter-select"
                            >
                                <option value="">All Intakes</option>
                                <option value="Sep">September</option>
                                <option value="Jan">January</option>
                                <option value="May">May</option>
                            </select>

                            {/* More Filters Toggle */}
                            <button
                                onClick={() => setIsExpanded(!isExpanded)}
                                className={`px-3 py-2 rounded-lg text-sm font-semibold border transition-colors whitespace-nowrap flex items-center gap-1 ${isExpanded ? 'bg-secondary text-white border-secondary' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'}`}
                            >
                                <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                                {isExpanded ? 'Less Filters' : 'More Filters'}
                            </button>
                        </div>

                        {/* Sort Options */}
                        <div className="flex items-center gap-2 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 border-gray-100 pt-3 md:pt-0">
                            <span className="text-xs text-muted font-medium whitespace-nowrap">Sort by:</span>
                            <select
                                value={filters.sort}
                                onChange={(e) => handleFilterChange('sort', e.target.value)}
                                className="bg-transparent text-sm font-bold text-gray-800 outline-none cursor-pointer hover:text-primary text-right"
                            >
                                <option value="relevance">Relevance</option>
                                <option value="ranking">Ranking</option>
                                <option value="duration_asc">Duration (Short → Long)</option>
                                <option value="duration_desc">Duration (Long → Short)</option>
                            </select>
                        </div>
                    </div>

                    {/* Expanded Row: Advanced Filters */}
                    {isExpanded && (
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2 border-t border-gray-100 animate-in fade-in slide-in-from-top-1 duration-200">
                            {/* City Input */}
                            <div>
                                <label className="filter-label">City</label>
                                <input
                                    type="text"
                                    placeholder="e.g. London, Boston"
                                    value={filters.city}
                                    onChange={(e) => handleFilterChange('city', e.target.value)}
                                    className="filter-input"
                                />
                            </div>


                            {/* IELTS Score */}
                            {type === 'courses' && (
                                <div>
                                    <label className="filter-label">IELTS Score</label>
                                    <select
                                        value={filters.ielts}
                                        onChange={(e) => handleFilterChange('ielts', e.target.value)}
                                        className="filter-input"
                                    >
                                        <option value="">Any Score</option>
                                        <option value="5.5">5.5+</option>
                                        <option value="6.0">6.0+</option>
                                        <option value="6.5">6.5+</option>
                                        <option value="7.0">7.0+</option>
                                    </select>
                                </div>
                            )}

                            {/* Clear All */}
                            <div className="flex items-end">
                                <button
                                    onClick={() => {
                                        const reset = { destination: '', city: '', level: '', intake: '', ielts: '', sort: filters.sort };
                                        setFilters(reset);
                                        applyFilters(reset);
                                    }}
                                    className="w-full py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors"
                                >
                                    Clear All Filters
                                </button>
                            </div>
                        </div>
                    )}
                </div>
            </div>

            <style jsx>{`
                .filter-select {
                    @apply bg-gray-50 border border-gray-200 text-sm font-medium rounded-lg px-3 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none cursor-pointer min-w-[130px];
                }
                .filter-input {
                    @apply w-full bg-white border border-gray-200 text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-secondary/20 focus:border-secondary outline-none;
                }
                .filter-label {
                    @apply block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wide;
                }
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
                .no-scrollbar {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            `}</style>
        </div>
    );
}
