"use client";

import { useState, useEffect } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import { motion } from 'framer-motion';

interface StickyFilterBarProps {
    type: 'courses' | 'universities';
}

export default function StickyFilterBar({ type }: StickyFilterBarProps) {
    const router = useRouter();
    const searchParams = useSearchParams();
    const [isSticky, setIsSticky] = useState(false);

    // Initial state from URL
    const [filters, setFilters] = useState({
        destination: searchParams.get('destination') || '',
        level: searchParams.get('level') || '',
        intake: searchParams.get('intake') || '',
        sort: 'relevance'
    });

    useEffect(() => {
        const handleScroll = () => {
            setIsSticky(window.scrollY > 100);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const updateFilters = (key: string, value: string) => {
        const newFilters = { ...filters, [key]: value };
        setFilters(newFilters);

        // Update URL
        const params = new URLSearchParams(searchParams.toString());
        if (value) params.set(key, value);
        else params.delete(key);

        router.push(`?${params.toString()}`, { scroll: false });
    };

    return (
        <div className={`transition-all duration-300 z-40 bg-white border-b border-gray-200 sticky top-[72px] ${isSticky ? 'shadow-md py-3' : 'py-4'}`}>
            <div className="container max-w-6xl mx-auto px-4">
                <div className="flex flex-col md:flex-row gap-4 items-center justify-between">

                    {/* Filters Row */}
                    <div className="flex gap-3 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 no-scrollbar items-center">
                        <select
                            value={filters.destination}
                            onChange={(e) => updateFilters('destination', e.target.value)}
                            className="bg-gray-50 border border-gray-200 text-sm font-medium rounded-lg px-3 py-2 focus:ring-secondary focus:border-secondary outline-none min-w-[140px]"
                        >
                            <option value="">All Destinations</option>
                            <option value="usa">USA</option>
                            <option value="uk">UK</option>
                            <option value="canada">Canada</option>
                            <option value="australia">Australia</option>
                        </select>

                        {type === 'courses' && (
                            <select
                                value={filters.level}
                                onChange={(e) => updateFilters('level', e.target.value)}
                                className="bg-gray-50 border border-gray-200 text-sm font-medium rounded-lg px-3 py-2 focus:ring-secondary focus:border-secondary outline-none min-w-[120px]"
                            >
                                <option value="">All Levels</option>
                                <option value="Bachelor">Bachelor</option>
                                <option value="Master">Master</option>
                                <option value="Diploma">Diploma</option>
                            </select>
                        )}

                        <select
                            value={filters.intake}
                            onChange={(e) => updateFilters('intake', e.target.value)}
                            className="bg-gray-50 border border-gray-200 text-sm font-medium rounded-lg px-3 py-2 focus:ring-secondary focus:border-secondary outline-none min-w-[120px]"
                        >
                            <option value="">All Intakes</option>
                            <option value="Sep">September</option>
                            <option value="Jan">January</option>
                            <option value="May">May</option>
                        </select>

                        <button
                            onClick={() => {
                                setFilters({ destination: '', level: '', intake: '', sort: 'relevance' });
                                router.push(`/search/${type}`);
                            }}
                            className="text-xs text-secondary font-semibold hover:underline whitespace-nowrap px-2"
                        >
                            Clear All
                        </button>
                    </div>

                    {/* Sort Dropdown & Results Count Stub */}
                    <div className="flex items-center gap-3 w-full md:w-auto justify-end border-t md:border-t-0 border-gray-100 pt-3 md:pt-0">
                        <span className="text-xs text-muted font-medium hidden lg:block">Sort by:</span>
                        <select
                            value={filters.sort}
                            onChange={(e) => updateFilters('sort', e.target.value)}
                            className="bg-white border text-right border-gray-200 text-sm font-semibold rounded-lg px-3 py-2 focus:ring-secondary focus:border-secondary outline-none cursor-pointer hover:bg-gray-50"
                        >
                            <option value="relevance">Relevance</option>
                            <option value="ranking">Ranking</option>
                            <option value="tuition_low">Tuition (Low to High)</option>
                            <option value="tuition_high">Tuition (High to Low)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    );
}
