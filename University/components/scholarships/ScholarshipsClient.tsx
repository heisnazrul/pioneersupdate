"use client";

import { useState } from 'react';
import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGraduationCap, faMapMarkerAlt, faCalendarAlt, faArrowRight } from '@fortawesome/free-solid-svg-icons';
import SectionHeading from '@/components/ui/SectionHeading';
import { ScholarshipApiData } from '@/lib/api';

interface ScholarshipsClientProps {
    initialScholarships: ScholarshipApiData[];
}

export default function ScholarshipsClient({ initialScholarships }: ScholarshipsClientProps) {
    const ITEMS_PER_PAGE = 12;
    const [currentPage, setCurrentPage] = useState(1);
    const [filterCountry, setFilterCountry] = useState("All Countries");

    // Enhance API data with defaults for missing UI fields
    const enrichedScholarships = initialScholarships.map(item => ({
        ...item,
        country: "International", // Default as API doesn't return this yet
        type: "All Levels" // Default as API doesn't return this yet
    }));

    const filteredItems = filterCountry === "All Countries"
        ? enrichedScholarships
        : enrichedScholarships.filter(s => s.country === filterCountry);

    const totalPages = Math.ceil(filteredItems.length / ITEMS_PER_PAGE);
    const paginatedItems = filteredItems.slice(
        (currentPage - 1) * ITEMS_PER_PAGE,
        currentPage * ITEMS_PER_PAGE
    );

    return (
        <section className="py-20">
            <div className="container mx-auto px-4 md:px-8 lg:px-12 xl:px-20">
                <div className="mb-12 flex flex-col md:flex-row justify-between items-end">
                    <SectionHeading
                        subtitle="Opportunities"
                        title="Available Scholarships"
                        className="mb-0"
                    />
                    <div className="mt-4 md:mt-0">
                        <select
                            value={filterCountry}
                            onChange={(e) => {
                                setFilterCountry(e.target.value);
                                setCurrentPage(1);
                            }}
                            className="px-4 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option>All Countries</option>
                            <option>UK</option>
                            <option>USA</option>
                            <option>Canada</option>
                            <option>Australia</option>
                            <option>International</option>
                        </select>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6 mb-16">
                    {paginatedItems.map((item) => (
                        <div key={item.id} className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                            <div className="flex items-start justify-between mb-4">
                                <div className="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                    <FontAwesomeIcon icon={faGraduationCap} className="text-xl" />
                                </div>
                                <span className="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                    {item.type}
                                </span>
                            </div>
                            <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                                {item.title}
                            </h3>
                            <div className="space-y-2 mb-6">
                                <div className="flex items-center text-gray-500 text-sm">
                                    <FontAwesomeIcon icon={faMapMarkerAlt} className="w-4 mr-2 opacity-70" />
                                    {item.country}
                                </div>
                                <div className="flex items-center text-gray-500 text-sm">
                                    <FontAwesomeIcon icon={faCalendarAlt} className="w-4 mr-2 opacity-70" />
                                    Deadline: {item.deadline}
                                </div>
                            </div>
                            <div className="pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span className="font-bold text-blue-900 text-sm">{item.amount}</span>
                                <Link href={`/scholarships/${item.slug}`} className="text-blue-600 hover:translate-x-1 transition-transform flex items-center gap-2 font-bold cursor-pointer">
                                    View Details <FontAwesomeIcon icon={faArrowRight} />
                                </Link>
                            </div>
                        </div>
                    ))}
                </div>

                {paginatedItems.length === 0 && (
                    <div className="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <p className="text-gray-500 font-medium">No scholarships found matching your filters.</p>
                    </div>
                )}

                {/* Pagination */}
                {totalPages > 1 && (
                    <div className="flex justify-center gap-2 mb-20">
                        <button
                            onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                            disabled={currentPage === 1}
                            className="w-10 h-10 rounded-lg bg-white text-gray-600 border border-gray-200 font-bold hover:bg-gray-50 flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            &larr;
                        </button>

                        {Array.from({ length: totalPages }, (_, i) => i + 1).map(page => (
                            <button
                                key={page}
                                onClick={() => setCurrentPage(page)}
                                className={`w-10 h-10 rounded-lg font-bold flex items-center justify-center transition-colors ${currentPage === page
                                    ? 'bg-blue-600 text-white shadow-md'
                                    : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
                                    }`}
                            >
                                {page}
                            </button>
                        ))}

                        <button
                            onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                            disabled={currentPage === totalPages}
                            className="w-10 h-10 rounded-lg bg-white text-gray-600 border border-gray-200 font-bold hover:bg-gray-50 flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            &rarr;
                        </button>
                    </div>
                )}
            </div>
        </section>
    );
}
