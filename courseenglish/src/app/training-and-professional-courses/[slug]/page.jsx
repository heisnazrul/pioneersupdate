"use client";

import { useEffect, useState, use } from "react";
import Image from "next/image";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar, faCalendarAlt, faUser, faMoneyBillWave, faMapMarkerAlt, faClock, faBookOpen } from "@fortawesome/free-solid-svg-icons";
import HeroDropdown from "@/app/components/HeroDropdown";
import HeroDatePicker from "@/app/components/HeroDatePicker";
import { useApi, formatCurrency, getImageUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function TrainingCourseDetailPage({ params }) {
    const { slug } = use(params);
    const router = useRouter();
    const { currency, language } = useCourseEnglishSettings();
    const isArabic = language === "ar";

    // API returns { school: {...}, course: {...} }
    const { data, loading, error } = useApi(`/courseenglish/training-and-professional-courses/${slug}`);

    // Helpers
    const t = (en, ar) => (isArabic && ar ? ar : en || "");

    const [weeks, setWeeks] = useState(1); // Default to 1, though training courses might be fixed
    const [startDate, setStartDate] = useState(null);

    const school = data?.school;
    const course = data?.course;

    useEffect(() => {
        if (course && course.start_date) {
            setStartDate(new Date(course.start_date));
        }
    }, [course]);

    if (loading) {
        return (
            <div className="p-20 text-center">
                <div className="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" />
            </div>
        );
    }

    if (error || !course) {
        return <div className="p-20 text-center text-red-500">Failed to load training course details.</div>;
    }

    const coursePrice = currency === "SAR" ? course.price_sar : course.price_gbp;
    // If price is per course (fixed), we might not need to multiply by weeks. 
    // Assuming price is Fixed for Training Courses based on typical business logic, 
    // but let's assume it's like others for now or check if 'weeks' is relevant. 
    // If 'weeks' is not relevant, we treat it as 1.
    const isFixedPrice = true; // Training courses are usually fixed duration/price
    const totalPrice = coursePrice; // * (isFixedPrice ? 1 : weeks); 
    const priceLabel = formatCurrency(totalPrice, currency) || "-";
    const locationLabel = school?.location || "";

    const handleBookNow = () => {
        if (!startDate) {
            alert(isArabic ? "يرجى اختيار تاريخ البدء" : "Please select a start date");
            return;
        }

        const query = new URLSearchParams({
            course_id: course.id,
            // weeks: weeks, // Might not be needed if fixed
            start_date: startDate.toISOString().split('T')[0],
            type: "training_courses"
        }).toString();

        router.push(`/training-and-professional-courses/${slug}/booking?${query}`);
    };

    return (
        <main className="min-h-screen pb-24 pt-0 lg:pt-6 bg-[#F8FAFC]" dir={isArabic ? "rtl" : "ltr"}>
            <div className="container mx-auto px-4">
                {/* Header / Breadcrumb */}
                <div className="mb-6 hidden lg:flex items-center justify-between">
                    <Link
                        href="/training-and-professional-courses"
                        className="flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-[#0057B7]"
                    >
                        <Image src="/assets/icons/arrow-left.svg" width={16} height={16} alt="Back" className={isArabic ? "" : "rotate-180"} />
                        {isArabic ? "العودة إلى القائمة" : "Back to List"}
                    </Link>
                </div>

                {/* Title Section */}
                <div className="mb-8 flex flex-col items-start gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-3xl font-semibold text-slate-900 md:text-4xl">
                            {t(course.name, course.ar_name)}
                        </h1>
                        <div className="mt-2 flex items-center gap-2 text-sm font-medium text-slate-500">
                            {school && (
                                <>
                                    <span className="flex items-center gap-1 text-[#0057B7]">
                                        <FontAwesomeIcon icon={faMapMarkerAlt} /> {t(school.name, school.ar_name)}
                                    </span>
                                    <span>•</span>
                                </>
                            )}
                            {school?.rating > 0 && (
                                <>
                                    <span className="flex items-center gap-1 text-[#F59E0B]">
                                        <FontAwesomeIcon icon={faStar} /> {school.rating}
                                    </span>
                                    <span>•</span>
                                </>
                            )}
                            {school?.flag && (
                                <Image src={school.flag} width={24} height={16} alt="Flag" className="rounded-sm" />
                            )}
                            <span>{locationLabel}</span>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-8 lg:grid-cols-12">
                    {/* Main Content */}
                    <div className="lg:col-span-8 space-y-8">

                        {/* Gallery / About */}
                        <div className="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6">
                            <div className="w-full md:w-1/2 space-y-3">
                                <div className="relative aspect-video w-full overflow-hidden rounded-2xl bg-gray-100">
                                    {course.image ? (
                                        <Image src={getImageUrl(course.image)} alt={t(course.name, course.ar_name)} fill className="object-cover" unoptimized />
                                    ) : (
                                        <div className="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                                    )}
                                </div>
                            </div>
                            <div className="flex-1 space-y-4">
                                <h2 className="text-xl font-medium text-slate-900">
                                    {isArabic ? "تفاصيل الدورة" : "Course Details"}
                                </h2>
                                <p className="text-sm leading-relaxed text-slate-600 whitespace-pre-line">
                                    {t(course.description, course.ar_description)}
                                </p>

                                {/* Key Features / Tags */}
                                <div className="flex flex-wrap gap-2 pt-2">
                                    {course.duration && (
                                        <span className="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                                            <FontAwesomeIcon icon={faClock} /> {course.duration} {isArabic ? "أسبوع" : "Weeks"}
                                        </span>
                                    )}
                                    {course.start_date && (
                                        <span className="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                                            <FontAwesomeIcon icon={faCalendarAlt} /> {new Date(course.start_date).toLocaleDateString()}
                                        </span>
                                    )}
                                    <span className="bg-yellow-50 text-yellow-600 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                                        <FontAwesomeIcon icon={faMoneyBillWave} /> {formatCurrency(coursePrice, currency)}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {/* Included Items if available, or Curriculum */}
                        {course.curriculum && (
                            <div className="rounded-3xl bg-white p-6 shadow-sm border border-gray-100">
                                <h3 className="text-xl font-medium text-slate-900 mb-4">{isArabic ? "المنهج الدراسي" : "Curriculum"}</h3>
                                <p className="text-sm text-slate-600 leading-relaxed">{t(course.curriculum, course.ar_curriculum)}</p>
                            </div>
                        )}

                    </div>

                    {/* Sidebar Booking Card */}
                    <div className="lg:col-span-4">
                        <div className="sticky top-4 space-y-6">
                            <div className="rounded-3xl border border-gray-100 bg-white p-6 shadow-xl">
                                <div className="rounded-2xl bg-[#F0F7FC] py-6 text-center border border-gray-100">
                                    <h3 className="text-3xl font-semibold tracking-tight text-[#1E293B]">{priceLabel}</h3>
                                    <p className="text-sm font-medium text-slate-500">
                                        {isArabic ? "الإجمالي" : "Total Price"}
                                    </p>
                                </div>

                                <div className="mt-6 space-y-4">
                                    {/* Date Picker */}
                                    <div>
                                        <label className="block text-xs font-medium text-slate-500 mb-1">{isArabic ? "تاريخ البدء" : "Start Date"}</label>
                                        <HeroDatePicker
                                            label={null}
                                            placeholder={isArabic ? "اختر التاريخ" : "Select Date"}
                                            onSelect={(date) => setStartDate(date)}
                                        />
                                        {startDate && (
                                            <div className="mt-1 text-xs text-slate-500 font-normal">
                                                {isArabic ? "المحدد:" : "Selected:"} {startDate.toLocaleDateString()}
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="mt-6 space-y-4 text-sm font-normal text-[#1E293B] border-t border-gray-100 pt-4">
                                    <div className="flex justify-between">
                                        <span className="font-medium">
                                            {t(course.name, course.ar_name)}
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-xs text-slate-500">
                                        <span>{isArabic ? "سعر الدورة" : "Course Price"}</span>
                                        <span>{priceLabel}</span>
                                    </div>
                                </div>

                                <div className="mt-6">
                                    <button type="button" onClick={handleBookNow} className="w-full rounded-xl bg-[#0057B7] p-4 text-sm font-medium text-white shadow-lg shadow-blue-200/50 transition hover:bg-[#004494]">
                                        {isArabic ? "احجز الآن" : "Book Now"}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    );
}
