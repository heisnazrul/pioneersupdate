"use client";

import { useEffect, useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import Button from '@/components/ui/Button';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faPlane,
    faGraduationCap,
    faMoneyBillWave,
    faCity,
    faArrowRight,
    faCheckCircle
} from '@fortawesome/free-solid-svg-icons';

// Map icon strings from CMS to actual icons
const iconMap: { [key: string]: any } = {
    faPlane,
    faGraduationCap,
    faMoneyBillWave,
    faCity
};

interface CmsContent {
    hero?: {
        badge: string;
        title: string;
        description: string;
    };
    categories?: Array<{
        title: string;
        description: string;
        icon: string;
        color: string;
    }>;
    trust_section?: {
        title: string;
        description: string;
        cta_text: string;
        cta_link: string;
    };
}

export default function StudentGuidePage() {
    const [content, setContent] = useState<CmsContent | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        async function fetchData() {
            try {
                const res = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/cms-pages/student-guide`);
                const json = await res.json();
                if (json.status === 'success') {
                    setContent(json.data.content);
                }
            } catch (error) {
                console.error("Failed to fetch CMS content", error);
            } finally {
                setLoading(false);
            }
        }
        fetchData();
    }, []);

    if (loading) return <div className="min-h-screen flex items-center justify-center">Loading...</div>;
    if (!content) return <div className="min-h-screen flex items-center justify-center">Failed to load content</div>;

    const { hero, categories, trust_section } = content;

    return (
        <main className="bg-white min-h-screen">
            {/* Hero Section */}
            {hero && (
                <section className="relative bg-[#003B5C] text-white pt-40 pb-32 overflow-hidden">
                    <div className="absolute inset-0 opacity-10">
                        <Image src="/hero.png" alt="Background" fill className="object-cover" />
                    </div>
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50 relative z-10">
                        <div className="max-w-4xl">
                            <span className="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-400/30 backdrop-blur-sm text-blue-200 text-xs font-bold uppercase tracking-wider mb-6">
                                {hero.badge}
                            </span>
                            <h1 className="text-5xl md:text-7xl font-extrabold leading-tight mb-8">
                                {hero.title}
                            </h1>
                            <p className="text-xl text-blue-100 max-w-2xl leading-relaxed mb-10">
                                {hero.description}
                            </p>
                        </div>
                    </div>
                </section>
            )}

            {/* Guides Categories Grid */}
            {categories && (
                <section className="py-24 relative">
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                            {categories.map((item, idx) => {
                                const Icon = iconMap[item.icon] || faGraduationCap;
                                return (
                                    <div key={idx} className="group flex gap-6 p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 bg-white">
                                        <div className={`flex-shrink-0 w-20 h-20 rounded-2xl flex items-center justify-center text-3xl ${item.color.includes('bg-') ? item.color : 'bg-blue-50 text-blue-600'}`}>
                                            <FontAwesomeIcon icon={Icon} />
                                        </div>
                                        <div>
                                            <h3 className="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                                                {item.title}
                                            </h3>
                                            <p className="text-gray-500 leading-relaxed mb-6">
                                                {item.description}
                                            </p>
                                            <div className="flex items-center gap-2 text-sm font-bold text-gray-900 group-hover:gap-4 transition-all cursor-pointer">
                                                Read Guide <FontAwesomeIcon icon={faArrowRight} className="text-blue-600" />
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </section>
            )}

            {/* Trust / Process Section */}
            {trust_section && (
                <section className="py-24 bg-gray-50 text-center">
                    <div className="px-4 md:px-10 xl:px-30 2xl:px-50">
                        <div className="max-w-3xl mx-auto">
                            <div className="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                                <FontAwesomeIcon icon={faCheckCircle} />
                            </div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">{trust_section.title}</h2>
                            <p className="text-gray-500 text-lg leading-relaxed mb-10">
                                {trust_section.description}
                            </p>
                            <Link href={trust_section.cta_link}>
                                <Button className="rounded-full px-10 py-5 text-xl shadow-xl shadow-blue-600/20">{trust_section.cta_text}</Button>
                            </Link>
                        </div>
                    </div>
                </section>
            )}
        </main>
    );
}
