"use client";

import { useState, useEffect, useMemo } from "react";
import Image from "next/image";
import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShare, faCheckCircle, faHeart, faExchangeAlt, faArrowLeft } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import mockDetails from "@/mocdata/institute-details.json";

function priceField(obj, field, currency) {
    if (!obj) return 0;
    const gbpKey = field ? `${field}_gbp` : "price_gbp";
    const sarKey = field ? `${field}_sar` : "price_sar";
    return currency === "SAR" ? (obj[sarKey] || 0) : (obj[gbpKey] || (obj.price || obj.amount || 0));
}

function fmtNum(value) {
    if (value === null || value === undefined) return "";
    return Math.round(Number(value)).toLocaleString("en-US", { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

function Price({ value, currency, className = "", size = "md", isNegative = false }) {
    if (value === null || value === undefined) return null;
    const num = fmtNum(Math.abs(value));
    const iconSize = size === "lg" ? 24 : size === "sm" ? 14 : 18;
    const sign = isNegative ? "-" : "";
    if (currency === "SAR") {
        return (
            <span className={`inline-flex items-center gap-1 ${className} ${isNegative ? "text-green-500" : ""}`} dir="ltr">
                <span className="font-medium">{sign}{num}</span>
                <img src="/assets/icons/sar.svg" alt="SAR" width={iconSize} height={iconSize} className="inline-block" />
            </span>
        );
    }
    return (
        <span className={`inline-flex items-center gap-0.5 ${className} ${isNegative ? "text-green-500" : ""}`} dir="ltr">
            <span className="font-medium">{sign}£{num}</span>
        </span>
    );
}

export default function MobileInstituteBooking({ slug: slugProp }) {
    const searchParams = useSearchParams();
    const { language, t } = useLocale();
    const isArabic = language === "ar";
    const currency = "GBP";

    const instituteData = mockDetails;

    const courseId = searchParams.get("course_id");
    const accommodationId = searchParams.get("accommodation_id");
    const pickupId = searchParams.get("pickup_id");
    const weeks = Number(searchParams.get("weeks")) || 1;
    const startDate = searchParams.get("start_date");
    const extrasIds = searchParams.get("extras") ? searchParams.get("extras").split(",") : [];

    const school = instituteData?.school || {};
    const courses = instituteData?.courses || [];
    const accommodations = instituteData?.accommodations || [];
    const pickUps = instituteData?.pickups || [];
    const insurances = instituteData?.insurances || [];
    const supplements = instituteData?.supplements || [];

    const selectedCourse = useMemo(() => courses.find(c => String(c.id) === String(courseId)), [courses, courseId]);
    const selectedAccommodation = useMemo(() => accommodations.find(a => String(a.id) === String(accommodationId)), [accommodations, accommodationId]);
    const selectedPickup = useMemo(() => pickUps.find(p => String(p.id) === String(pickupId)), [pickUps, pickupId]);

    const allExtras = useMemo(() => [...insurances, ...supplements], [insurances, supplements]);
    const selectedExtras = useMemo(() => allExtras.filter(e => extrasIds.includes(String(e.id))), [allExtras, extrasIds]);

    const regFeeObj = instituteData?.registration_fee;
    const registrationFee = regFeeObj ? priceField(regFeeObj, "amount", currency) : 50;

    const discounts = instituteData?.discounts || [];
    const pioneersDiscounts = instituteData?.pioneers_discounts || [];
    const bestDiscount = discounts.length > 0 ? Math.max(...discounts.map(d => d.discount_percentage || 0)) : 0;
    const pioneersDisc = pioneersDiscounts.length > 0 ? pioneersDiscounts[0] : null;
    const discountPercent = bestDiscount > 0 ? bestDiscount : (pioneersDisc ? 20 : 0);

    const coursePrice = selectedCourse ? priceField(selectedCourse, "price", currency) * weeks : 0;
    const accPrice = selectedAccommodation ? priceField(selectedAccommodation, "fee_per_week", currency) * weeks : 0;
    const pickupPrice = selectedPickup ? (priceField(selectedPickup, "price", currency) || 0) : 0;
    const extrasPrice = selectedExtras.reduce((sum, e) => sum + (priceField(e, "price", currency) || priceField(e, "amount", currency) || 0), 0);

    const subtotal = coursePrice + accPrice + pickupPrice + extrasPrice + registrationFee;
    const discountAmount = discountPercent > 0 ? subtotal * (discountPercent / 100) : 0;
    const totalPrice = subtotal - discountAmount;

    const [formData, setFormData] = useState({ name: "", email: "", phone: "" });
    const [submitting, setSubmitting] = useState(false);
    const slug = slugProp || "lsi-education-london";

    const loc = (en, ar) => (isArabic && ar) ? ar : en;
    const l = (key) => t(`pages.institute_details.booking.${key}`) || t(`pages.institute_details.${key}`);

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const onSendRequest = (e) => {
        e && e.preventDefault();
        if (!formData.name || !formData.email || !formData.phone) {
            alert(isArabic ? "يرجى ملء جميع الحقول المطلوبة" : "Please fill in all required fields");
            return;
        }
        setSubmitting(true);
        // Simulate API call
        setTimeout(() => {
            setSubmitting(false);
            alert(isArabic ? "تم إرسال الطلب بنجاح!" : "Request sent successfully!");
        }, 1500);
    };

    const schoolName = loc(school.name, school.ar_name);
    const location = loc(school.city || school.location, school.city_ar);
    const formattedStartDate = startDate
        ? new Date(startDate).toLocaleDateString(isArabic ? "ar-SA" : "en-GB", { day: "numeric", month: "long", year: "numeric" })
        : "-";

    return (
        <div className="min-h-screen bg-[#F8FAFC] pb-24" dir={isArabic ? "rtl" : "ltr"}>
            
            {/* Header */}
            <div className="bg-white border-b border-gray-100 px-4 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <Link href={`/language-institutes/${slug}`} className="h-10 w-10 flex items-center justify-center rounded-full bg-gray-50 text-slate-600">
                    <FontAwesomeIcon icon={faArrowLeft} className={isArabic ? "rotate-180" : ""} />
                </Link>
                <h1 className="text-lg font-semibold text-slate-900">{l("reviewConfirm")}</h1>
                <div className="w-10"></div>
            </div>

            <div className="px-4 pt-6 space-y-6">
                
                {/* Institute Card */}
                <div className="bg-white rounded-2xl border border-gray-100 p-4 flex gap-4 items-start shadow-sm">
                    <div className="relative w-20 h-20 shrink-0 rounded-xl overflow-hidden border border-gray-50">
                        <img src={school.image || "/assets/hero.png"} alt={schoolName} className="w-full h-full object-cover" />
                    </div>
                    <div className="flex-1 flex flex-col justify-center h-full">
                        <div className="flex justify-between items-start mb-1">
                            <h3 className="font-medium text-slate-900 text-base leading-tight flex-1" dir="ltr">{schoolName}</h3>
                            <Link href={`/language-institutes/${slug}`} className="bg-gray-50 text-[#0057B7] text-[10px] font-medium px-2 py-1 rounded">
                                {l("change")}
                            </Link>
                        </div>
                        <div className="text-xs text-slate-500">
                            {location}
                        </div>
                    </div>
                </div>

                {/* Price Summary */}
                <div className="bg-white rounded-3xl border border-gray-100 p-5 shadow-sm">
                    <h3 className="font-semibold text-lg text-slate-900 mb-5">{l("priceSummary")}</h3>

                    <div className="space-y-4">
                        {selectedCourse && (
                            <div className="flex justify-between items-center text-sm gap-4">
                                <span className="text-slate-600 flex-1">{loc(selectedCourse.name, selectedCourse.ar_name)} ({weeks} {l("weeks")})</span>
                                <Price value={coursePrice} currency={currency} className="font-medium text-slate-900" />
                            </div>
                        )}

                        {selectedAccommodation && (
                            <div className="flex justify-between items-center text-sm gap-4">
                                <span className="text-slate-600 flex-1">{loc(selectedAccommodation.title, selectedAccommodation.ar_title)} ({weeks} {l("weeks")})</span>
                                <Price value={accPrice} currency={currency} className="font-medium text-slate-900" />
                            </div>
                        )}

                        <div className="flex justify-between items-center text-sm gap-4">
                            <span className="text-slate-600 flex-1">{l("registrationFee")}</span>
                            <Price value={registrationFee} currency={currency} className="font-medium text-slate-900" />
                        </div>

                        {selectedPickup && (
                            <div className="flex justify-between items-center text-sm gap-4">
                                <span className="text-slate-600 flex-1">{loc(selectedPickup.route, selectedPickup.ar_route)}</span>
                                <Price value={pickupPrice} currency={currency} className="font-medium text-slate-900" />
                            </div>
                        )}

                        {selectedExtras.map((ex, i) => (
                            <div key={i} className="flex justify-between items-center text-sm gap-4">
                                <span className="text-slate-600 flex-1">{loc(ex.name, ex.ar_name)}</span>
                                <Price value={priceField(ex, "price", currency) || priceField(ex, "amount", currency)} currency={currency} className="font-medium text-slate-900" />
                            </div>
                        ))}

                        {discountAmount > 0 && (
                            <div className="flex justify-between items-center text-sm gap-4 text-green-500">
                                <span className="font-medium flex-1">{l("discount")}</span>
                                <Price value={discountAmount} currency={currency} isNegative className="font-semibold" />
                            </div>
                        )}
                    </div>

                    <div className="mt-5 pt-5 border-t border-gray-100 flex justify-between items-center gap-4">
                        <div className="text-start">
                            <h4 className="text-sm font-medium text-slate-500">{l("total")}</h4>
                            <p className="text-[10px] text-slate-400">{l("totalInclude")}</p>
                        </div>
                        <div className="text-2xl font-bold text-[#0B5DB6]">
                            <Price value={totalPrice} currency={currency} size="lg" />
                        </div>
                    </div>
                </div>

                {/* Details Cards */}
                <div className="space-y-4">
                    {/* Course Details */}
                    <div className="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                        <div className="flex justify-between items-center mb-4">
                            <h4 className="font-medium text-slate-900 text-sm">{l("courseDetails")}</h4>
                            <Link href={`/language-institutes/${slug}`} className="bg-gray-50 border border-gray-100 text-slate-500 text-[10px] font-medium px-3 py-1 rounded">
                                {l("change")}
                            </Link>
                        </div>
                        <div className="space-y-1">
                            <p className="font-medium text-slate-800 text-sm">{selectedCourse ? loc(selectedCourse.name, selectedCourse.ar_name) : "-"}</p>
                            <p className="text-xs text-slate-500">{l("startDate")}: {formattedStartDate}</p>
                            <p className="text-xs text-slate-500">{l("duration")}: {weeks} {l("weeks")}</p>
                        </div>
                    </div>

                    {/* Acc Details */}
                    {selectedAccommodation && (
                        <div className="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <div className="flex justify-between items-center mb-4">
                                <h4 className="font-medium text-slate-900 text-sm">{l("accDetails")}</h4>
                                <Link href={`/language-institutes/${slug}`} className="bg-gray-50 border border-gray-100 text-slate-500 text-[10px] font-medium px-3 py-1 rounded">
                                    {l("change")}
                                </Link>
                            </div>
                            <div className="space-y-1">
                                <p className="font-medium text-slate-800 text-sm">{loc(selectedAccommodation.title, selectedAccommodation.ar_title)}</p>
                                <p className="text-xs text-slate-500">{selectedAccommodation.features}</p>
                            </div>
                        </div>
                    )}

                    {/* Extras Details */}
                    {(selectedPickup || selectedExtras.length > 0) && (
                        <div className="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <div className="flex justify-between items-center mb-4">
                                <h4 className="font-medium text-slate-900 text-sm">{l("addServices")}</h4>
                                <Link href={`/language-institutes/${slug}`} className="bg-gray-50 border border-gray-100 text-slate-500 text-[10px] font-medium px-3 py-1 rounded">
                                    {l("change")}
                                </Link>
                            </div>
                            <div className="space-y-3">
                                {selectedPickup && (
                                    <div className="flex items-center gap-2">
                                        <div className="w-5 h-5 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-[10px]">
                                            <FontAwesomeIcon icon={faCheckCircle} />
                                        </div>
                                        <div>
                                            <p className="font-medium text-slate-900 text-sm">{l("pickup")}</p>
                                            <p className="text-xs text-slate-500">{loc(selectedPickup.route, selectedPickup.ar_route)}</p>
                                        </div>
                                    </div>
                                )}
                                {selectedExtras.map((ex, i) => (
                                    <div key={i} className="flex items-center gap-2">
                                        <div className="w-5 h-5 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-[10px]">
                                            <FontAwesomeIcon icon={faCheckCircle} />
                                        </div>
                                        <div>
                                            <p className="font-medium text-slate-900 text-sm">{loc(ex.name, ex.ar_name)}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>

                {/* Contact Form */}
                <div className="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mt-6">
                    <h3 className="text-xl font-semibold text-slate-900 mb-2">{l("contactDetails")}</h3>
                    <p className="text-sm text-slate-500 mb-6">{l("contactSubtitle")}</p>
                    
                    <form className="space-y-4" onSubmit={onSendRequest}>
                        <div>
                            <label className="block text-sm font-medium text-slate-900 mb-2">
                                <span className="text-red-500">*</span> {l("fullName")}
                            </label>
                            <input
                                type="text"
                                name="name"
                                value={formData.name}
                                onChange={handleInputChange}
                                placeholder={l("enterFullName")}
                                className="w-full h-12 rounded-xl border border-gray-200 px-4 text-sm outline-none focus:border-[#0057B7] placeholder:text-gray-300"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-slate-900 mb-2">
                                <span className="text-red-500">*</span> {l("email")}
                            </label>
                            <input
                                type="email"
                                name="email"
                                value={formData.email}
                                onChange={handleInputChange}
                                placeholder={l("enterEmail")}
                                className="w-full h-12 rounded-xl border border-gray-200 px-4 text-sm outline-none focus:border-[#0057B7] placeholder:text-gray-300"
                                dir="ltr"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-slate-900 mb-2">
                                <span className="text-red-500">*</span> {l("mobile")}
                            </label>
                            <div className="flex gap-2" dir="ltr">
                                <div className="w-20 h-12 flex items-center justify-center gap-1 rounded-xl border border-gray-200 bg-gray-50 px-2 shrink-0">
                                    <img src="https://flagcdn.com/w40/sa.png" alt="SA" className="w-5 mx-1 rounded-sm" />
                                    <span className="text-sm font-medium text-slate-700">+966</span>
                                </div>
                                <input
                                    type="tel"
                                    name="phone"
                                    value={formData.phone}
                                    onChange={handleInputChange}
                                    placeholder="5xxxxxxx"
                                    className="flex-1 h-12 rounded-xl border border-gray-200 px-4 text-sm outline-none focus:border-[#0057B7] placeholder:text-gray-300"
                                />
                            </div>
                        </div>

                        <div className="pt-4">
                            <button type="submit" disabled={submitting} className="w-full h-14 rounded-xl bg-[#0070CD] text-white font-medium text-lg hover:bg-[#005EB3] transition shadow-lg shadow-blue-100/50 disabled:opacity-50">
                                {submitting ? l("processing") : l("sendRequest")}
                            </button>

                            <div className="flex items-center justify-center gap-2 text-[11px] text-slate-400 mt-4 text-center">
                                <FontAwesomeIcon icon={faCheckCircle} />
                                <span>{l("disclaimer")}</span>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    );
}
