"use client";

import { useState, useEffect, useMemo } from "react";
import Image from "next/image";
import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShare, faCheckCircle, faHeart, faExchangeAlt } from "@fortawesome/free-solid-svg-icons";
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
    return Number(value).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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

export default function DesktopInstituteBooking({ params }) {
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
    const [toastMsg, setToastMsg] = useState(null);
    const slug = params?.slug || "lsi-education-london";

    const loc = (en, ar) => (isArabic && ar) ? ar : en;
    const l = (key) => t(`pages.institute_details.booking.${key}`) || t(`pages.institute_details.${key}`);

    const showToast = (msg) => {
        setToastMsg(msg);
        setTimeout(() => setToastMsg(null), 2500);
    };

    const handleShare = () => {
        if (typeof window !== "undefined") {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showToast(isArabic ? 'تم نسخ الرابط!' : 'Link copied!');
            });
        }
    };

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

    return (
        <div className="container mx-auto px-4 pb-24 pt-6" dir={isArabic ? "rtl" : "ltr"}>
            {/* Header Actions & Navigation */}
            <div className="mb-8 flex items-center justify-between">
                <Link href={`/language-institutes/${slug}`} className="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-[#0057B7] transition">
                    <img src="/assets/icons/arrow-left.svg" width={16} height={16} alt="Back" className={isArabic ? "" : "rotate-180"} />
                    {l("backToList")}
                </Link>
                <div className="flex items-center gap-4 relative">
                    <button className="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:border-[#0057B7] hover:text-[#0057B7] transition">
                        <FontAwesomeIcon icon={faExchangeAlt} className="h-4 w-4" />
                        {l("addCompare")}
                    </button>
                    <button className="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:border-red-400 hover:text-red-500 transition">
                        <FontAwesomeIcon icon={faHeart} className="h-4 w-4" />
                        {l("addFavorite")}
                    </button>
                    <button onClick={handleShare} className="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:border-[#0057B7] hover:text-[#0057B7] transition">
                        <FontAwesomeIcon icon={faShare} className="h-4 w-4" />
                        {l("share")}
                    </button>
                    {toastMsg && (
                        <div className="absolute -bottom-12 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-lg z-50">
                            {toastMsg}
                        </div>
                    )}
                </div>
            </div>

            <div className="mb-8 text-center md:text-start">
                <h1 className="text-2xl lg:text-4xl font-semibold text-slate-900">{l("reviewConfirm")}</h1>
                <p className="mt-2 text-sm text-slate-500">{l("reviewSubtitle")}</p>
            </div>

            {/* Main Content: 2-Column Layout */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                {/* Contact Form Column */}
                <div className="order-2 lg:order-1">
                    <div className="bg-[#F8FAFC] lg:bg-white rounded-[2rem] p-6 lg:p-10 lg:shadow-sm lg:border lg:border-gray-100">
                        <h3 className="text-2xl font-semibold text-slate-900 mb-2">{l("contactDetails")}</h3>
                        <p className="text-sm text-slate-500 mb-8">{l("contactSubtitle")}</p>
                        
                        <form className="space-y-6" onSubmit={onSendRequest}>
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
                                    <div className="w-24 h-12 flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-2 shrink-0">
                                        <img src="https://flagcdn.com/w40/sa.png" alt="SA" className="w-6 mx-1 rounded-sm" />
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

                            <button type="submit" disabled={submitting} className="w-full h-14 mt-6 rounded-xl bg-[#0070CD] text-white font-medium text-lg hover:bg-[#005EB3] transition shadow-lg shadow-blue-100/50 disabled:opacity-50">
                                {submitting ? l("processing") : l("sendRequest")}
                            </button>

                            <div className="flex items-center justify-center gap-2 text-xs text-slate-400 mt-4">
                                <FontAwesomeIcon icon={faCheckCircle} />
                                <span>{l("disclaimer")}</span>
                            </div>
                        </form>
                    </div>
                </div>

                {/* Summary Column */}
                <div className="order-1 lg:order-2 space-y-6">

                    {/* Institute Card */}
                    <div className="bg-white rounded-2xl border border-gray-100 p-4 flex gap-4 items-start shadow-sm">
                        <div className="relative w-[88px] h-[88px] shrink-0 rounded-xl overflow-hidden border border-gray-50">
                            <img src={school.image || "/assets/hero.png"} alt={schoolName} className="w-full h-full object-cover" />
                        </div>
                        <div className="flex-1 flex flex-col items-start justify-center h-full py-1">
                            <div className="w-full flex justify-between items-start mb-1">
                                <h3 className="font-medium text-slate-900 text-base leading-tight flex-1" dir="ltr">{schoolName}</h3>
                                <Link href={`/language-institutes/${slug}`} className="bg-gray-50 text-slate-500 text-[10px] font-medium px-3 py-1 rounded hover:bg-gray-100 transition whitespace-nowrap ml-3">
                                    {l("change")}
                                </Link>
                            </div>
                            <div className="flex items-center gap-1 text-xs text-slate-400">
                                <span>{location}</span>
                            </div>
                        </div>
                    </div>

                    {/* Price Summary */}
                    <div className="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                        <h3 className="font-semibold text-lg text-slate-900 mb-6">{l("priceSummary")}</h3>

                        <div className="space-y-4">
                            {selectedCourse && (
                                <div className="flex justify-between items-center text-sm gap-6">
                                    <span className="text-slate-600 font-normal flex-1">{loc(selectedCourse.name, selectedCourse.ar_name)} ({weeks} {l("weeks")})</span>
                                    <Price value={coursePrice} currency={currency} className="font-medium text-slate-900 shrink-0" />
                                </div>
                            )}

                            {selectedAccommodation && (
                                <div className="flex justify-between items-center text-sm gap-6">
                                    <span className="text-slate-600 font-normal flex-1">{loc(selectedAccommodation.title, selectedAccommodation.ar_title)} ({weeks} {l("weeks")})</span>
                                    <Price value={accPrice} currency={currency} className="font-medium text-slate-900 shrink-0" />
                                </div>
                            )}

                            <div className="flex justify-between items-center text-sm gap-6">
                                <span className="text-slate-600 font-normal flex-1">{l("registrationFee")}</span>
                                <Price value={registrationFee} currency={currency} className="font-medium text-slate-900 shrink-0" />
                            </div>

                            {selectedPickup && (
                                <div className="flex justify-between items-center text-sm gap-6">
                                    <span className="text-slate-600 font-normal flex-1">{loc(selectedPickup.route, selectedPickup.ar_route)}</span>
                                    <Price value={pickupPrice} currency={currency} className="font-medium text-slate-900 shrink-0" />
                                </div>
                            )}

                            {selectedExtras.map((ex, i) => (
                                <div key={i} className="flex justify-between items-center text-sm gap-6">
                                    <span className="text-slate-600 font-normal flex-1">{loc(ex.name, ex.ar_name)}</span>
                                    <Price value={priceField(ex, "price", currency) || priceField(ex, "amount", currency)} currency={currency} className="font-medium text-slate-900 shrink-0" />
                                </div>
                            ))}

                            {discountAmount > 0 && (
                                <div className="flex justify-between items-center text-sm gap-6 text-green-500">
                                    <span className="font-medium flex-1">{l("discount")}</span>
                                    <Price value={discountAmount} currency={currency} isNegative className="shrink-0 font-semibold" />
                                </div>
                            )}
                        </div>

                        <div className="mt-6 pt-6 border-t border-gray-100 flex justify-between items-end gap-4">
                            <div>
                                <h4 className="text-sm font-medium text-slate-500 mb-1">{l("total")}</h4>
                                <p className="text-[10px] text-slate-400">{l("totalInclude")}</p>
                            </div>
                            <div className="text-3xl font-bold text-[#0B5DB6]">
                                <Price value={totalPrice} currency={currency} size="lg" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
