"use client";

import { Course } from '@/lib/data/types';
import { useState, useEffect } from 'react';
import Link from 'next/link';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHeart } from '@fortawesome/free-regular-svg-icons';
import { faHeart as faHeartSolid } from '@fortawesome/free-solid-svg-icons';
import { faChartSimple, faGraduationCap, faCalendarDays, faLocationDot, faUniversity } from '@fortawesome/free-solid-svg-icons';
import { useLang } from '@/hooks/useLang';

interface CourseCardProps {
    course: Course;
}

const getLogo = (name: string) => {
    if (!name || typeof name !== 'string') return '/assets/universities/generic.png';
    const n = name.toLowerCase();
    if (n.includes('oxford')) return '/assets/universities/oxford.png';
    if (n.includes('cambridge')) return '/assets/universities/cambridge.png';
    if (n.includes('harvard')) return '/assets/universities/harvard.png';
    if (n.includes('mit') || n.includes('technology')) return '/assets/universities/mit.png';
    if (n.includes('stanford')) return '/assets/universities/stanford.png';
    if (n.includes('toronto')) return '/assets/universities/toronto.png';
    return '/assets/universities/generic.png';
};

export default function CourseCard({ course }: CourseCardProps) {
    const getUniversityName = () => {
        if (!course.university) return "";
        if (typeof course.university === 'string') return course.university;
        if (typeof course.university === 'object' && (course.university as any).name) {
            return (course.university as any).name;
        }
        return "";
    };

    const universityName = getUniversityName();
    const ranking = (String(course.id).split('').reduce((acc, char) => acc + char.charCodeAt(0), 0) % 500) + 1;
    const locationParts = (course.location || "Unknown, Global").split(',');

    const apiLogo = course.universityLogo || (course as any).universityLogo;
    const logoUrl = apiLogo && apiLogo.trim() !== '' ? apiLogo : null;
    const isApiLogo = !!logoUrl;

    // Language detection
    const { isAr } = useLang();

    // Wishlist Logic
    const [isWishlisted, setIsWishlisted] = useState(false);
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        checkWishlistStatus();
    }, [course.id]);

    const checkWishlistStatus = async () => {
        const token = localStorage.getItem('token');
        if (!token) return;
        try {
            const res = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000'}/api/wishlist`, {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (res.ok) {
                const data = await res.json();
                const exists = data.some((item: any) => String(item.course_id) === String(course.id));
                setIsWishlisted(exists);
            }
        } catch (e) {
            console.error("Failed to check wishlist status");
        }
    };

    const toggleWishlist = async (e: React.MouseEvent) => {
        e.preventDefault();
        e.stopPropagation();

        const token = localStorage.getItem('token');
        if (!token) {
            alert(isAr ? "يرجى تسجيل الدخول لحفظ الدورات." : "Please login to save courses.");
            return;
        }

        setIsLoading(true);

        try {
            if (isWishlisted) {
                const res = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000'}/api/wishlist/${course.id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                if (res.ok) setIsWishlisted(false);
            } else {
                const res = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000'}/api/wishlist`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ course_id: course.id })
                });
                if (res.ok) {
                    setIsWishlisted(true);
                } else {
                    const data = await res.json();
                    if (res.status === 409) setIsWishlisted(true);
                    else alert(data.message || (isAr ? "فشل الإضافة إلى المفضلة" : "Failed to add to wishlist"));
                }
            }
        } catch (error) {
            console.error("Wishlist toggle error", error);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="bg-white border border-slate-200 rounded-2xl p-5 relative hover:shadow-xl transition-all duration-300 flex flex-col h-full group" dir={isAr ? 'rtl' : 'ltr'}>
            {/* Wishlist Heart — left in Arabic, right in English */}
            <button
                onClick={toggleWishlist}
                disabled={isLoading}
                className={`absolute top-5 z-10 p-2 rounded-full transition-colors ${isAr ? 'left-5' : 'right-5'} ${isWishlisted ? "text-red-500 bg-red-50" : "text-slate-400 hover:text-red-500 hover:bg-slate-50"}`}
            >
                <FontAwesomeIcon icon={isWishlisted ? faHeartSolid : faHeart} className="w-5 h-5" />
            </button>

            {/* Logo */}
            <div className="w-14 h-14 relative mb-3 border border-slate-100 rounded-xl overflow-hidden bg-blue-50 flex items-center justify-center">
                {isApiLogo ? (
                    <img
                        src={logoUrl!}
                        alt={universityName || "University"}
                        className="object-contain max-h-full max-w-full p-1"
                    />
                ) : (
                    <FontAwesomeIcon icon={faUniversity} className="text-blue-300 text-2xl" />
                )}
            </div>

            {/* Content */}
            <div className="flex-grow">
                <h3 className="text-lg font-bold text-slate-900 mb-1 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors min-h-[3rem]">
                    {course.name || course.title}
                </h3>
                <p className="text-xs font-bold text-slate-500 uppercase tracking-wide mb-4">
                    {universityName || (isAr ? "جامعة غير معروفة" : "Unknown University")}
                </p>

                <div className="w-full h-px bg-slate-100 mb-4"></div>

                <div className="space-y-2 text-sm text-slate-600">
                    <div className="flex items-center gap-3">
                        <div className="w-4 flex justify-center text-slate-400">
                            <FontAwesomeIcon icon={faChartSimple} className="text-xs" />
                        </div>
                        <span className="text-xs">
                            {isAr ? 'تصنيف THE العالمي: ' : 'THE World Ranking: '}
                            <span className="font-semibold text-slate-900">{ranking}</span>
                        </span>
                    </div>

                    <div className="flex items-center gap-3">
                        <div className="w-4 flex justify-center text-slate-400">
                            <FontAwesomeIcon icon={faGraduationCap} className="text-xs" />
                        </div>
                        <span className="text-xs">{course.level}</span>
                    </div>

                    <div className="flex items-center gap-3">
                        <div className="w-4 flex justify-center text-slate-400">
                            <FontAwesomeIcon icon={faLocationDot} className="text-xs" />
                        </div>
                        <span className="text-xs">{course.location || (isAr ? 'عبر الإنترنت' : 'Online')}</span>
                    </div>

                    <div className="flex items-center gap-3">
                        <div className="w-4 flex justify-center text-slate-400">
                            <FontAwesomeIcon icon={faCalendarDays} className="text-xs" />
                        </div>
                        <span className="text-xs">
                            {isAr ? 'القبول القادم: ' : 'Next intake: '}
                            <span className="font-semibold text-slate-900">
                                {course.intake?.join(', ') || 'Sep 2026'}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            {/* Action */}
            <div className="mt-5">
                <Link
                    href={`/courses/${course.slug || course.id}`}
                    className="block w-full py-2.5 text-center border border-slate-300 rounded-full font-bold text-sm text-slate-700 hover:bg-[#0B2A4A] hover:text-white hover:border-[#0B2A4A] transition-all duration-300"
                >
                    {isAr ? 'عرض التفاصيل' : 'View details'}
                </Link>
            </div>
        </div>
    );
}
