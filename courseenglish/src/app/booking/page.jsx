"use client";

import { Suspense, useMemo, useState, useEffect } from "react";
import Image from "next/image";
import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShare, faCheckCircle, faMapMarkerAlt, faPencilAlt, faHeart, faExchangeAlt } from "@fortawesome/free-solid-svg-icons";
import { useApi, buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import { useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";
import BookingVerificationModal from "@/app/components/BookingVerificationModal";
import BookingSuccessModal from "@/app/components/BookingSuccessModal";

/* ── Helpers ──────────────────────────────────────────────── */
function priceField(obj, field, currency) {
    if (!obj) return 0;
    const gbpKey = field ? `${field}_gbp` : "price_gbp";
    const sarKey = field ? `${field}_sar` : "price_sar";
    return currency === "SAR" ? (obj[sarKey] || 0) : (obj[gbpKey] || 0);
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
                <img src="/assets/sar-black.svg" alt="SAR" width={iconSize} height={iconSize} className="inline-block" />
            </span>
        );
    }
    return (
        <span className={`inline-flex items-center gap-0.5 ${className} ${isNegative ? "text-green-500" : ""}`} dir="ltr">
            <span className="font-medium">{sign}£{num}</span>
        </span>
    );
}

function t(en, ar, isArabic) {
    return isArabic && ar ? ar : (en || "");
}

/* ── Labels ────────────────────────────────────────────────── */
const LABELS = {
    backToList: { en: "Back to List", ar: "العودة الى قائمة المعاهد" },
    share: { en: "Share", ar: "مشاركة" },
    addToWishlist: { en: "Add to Wishlist", ar: "اضافة للمفضلة" },
    inWishlist: { en: "In Wishlist", ar: "مضاف للمفضلة" },
    addToCompare: { en: "Add to Compare", ar: "اضافة للمقارنة" },
    inCompare: { en: "In Compare", ar: "مضاف للمقارنة" },
    reviewBooking: { en: "Review Booking", ar: "مراجعة الحجز" },
    reviewConfirm: { en: "Review and Confirm Booking", ar: "مراجعة وتأكيد الحجز" },
    reviewSubtitle: { en: "Review booking details and contact info before sending request", ar: "راجع تفاصيل الحجز ومعلومات الاتصال قبل إرسال الطلب" },
    contactDetails: { en: "Contact Details", ar: "بيانات التواصل" },
    contactSubtitle: { en: "We will contact you via WhatsApp to confirm details", ar: "سيتم التواصل معك عبر واتساب لتأكيد التفاصيل واختيار الأنسب لك" },
    fullName: { en: "Full Name", ar: "الاسم بالكامل" },
    enterFullName: { en: "Enter Full Name", ar: "أدخل الاسم بالكامل" },
    email: { en: "Email Address", ar: "البريد الالكتروني" },
    enterEmail: { en: "Enter Email Address", ar: "أدخل البريد الالكتروني" },
    mobile: { en: "Mobile Number", ar: "رقم الجوال" },
    sendRequest: { en: "Send Request", ar: "ارسال الطلب" },
    disclaimer: { en: "After sending, one of our consultants will contact you via WhatsApp shortly", ar: "بعد الإرسال سيتواصل معك أحد مستشارينا عبر واتساب خلال وقت قصير" },
    priceSummary: { en: "Price Summary", ar: "ملخص السعر" },
    courseDetails: { en: "Course Details", ar: "تفاصيل الدورة" },
    accDetails: { en: "Accommodation Details", ar: "تفاصيل السكن" },
    addServices: { en: "Additional Services", ar: "الخدمات الاضافية" },
    change: { en: "Change", ar: "تغيير" },
    course: { en: "Course", ar: "دورة" },
    accommodation: { en: "Accommodation", ar: "إقامة" },
    registrationFee: { en: "Registration Fee", ar: "رسوم التسجيل" },
    discount: { en: "Discount", ar: "خصم" },
    total: { en: "Total", ar: "الاجمالي" },
    weeks: { en: "weeks", ar: "اسابيع" },
    startDate: { en: "Start Date", ar: "بداية الدورة" },
    duration: { en: "Duration", ar: "مدة الدراسة" },
    pickup: { en: "Airport Pickup", ar: "الاستقبال من المطار" },
    insurance: { en: "Insurance", ar: "تأمين" },
    supplements: { en: "Supplements", ar: "اضافات" },
    totalInclude: { en: "Total includes all fees", ar: "السعر شامل جميع الرسوم" },
};

function l(key, isArabic) {
    const obj = LABELS[key];
    return obj ? t(obj.en, obj.ar, isArabic) : key;
}

/* ── Content Component ────────────────────────────────────── */
function BookingConfirmationContent() {
    const searchParams = useSearchParams();
    const { currency, isArabic } = useCourseEnglishSettings();
    const { toggleWishlist, toggleCompare, isInWishlist, isInCompare } = useCourseEnglishInteractions();

    // Read params
    const slug = searchParams.get("slug");
    const courseId = searchParams.get("course_id");
    const accommodationId = searchParams.get("accommodation_id");
    const pickupId = searchParams.get("pickup_id");
    const weeks = Number(searchParams.get("weeks")) || 1;
    const startDate = searchParams.get("start_date");
    const extrasIds = searchParams.get("extras") ? searchParams.get("extras").split(",") : [];
    const courseType = searchParams.get("type") || "language_courses";
    const accAge = searchParams.get("acc_age") ? Number(searchParams.get("acc_age")) : null;

    // Fetch data
    const { data: instituteData, loading, error } = useApi(slug ? `/courseenglish/language-institutes/${slug}` : null);

    const school = instituteData?.school || {};
    const courses = instituteData?.courses || [];
    const accommodations = instituteData?.accommodations || [];
    const pickUps = instituteData?.pickups || [];
    const insurances = instituteData?.insurances || [];
    const supplements = instituteData?.supplements || [];

    // Resolve selected items
    const selectedCourse = useMemo(() => courses.find(c => String(c.id) === String(courseId)), [courses, courseId]);
    const selectedAccommodation = useMemo(() => accommodations.find(a => String(a.id) === String(accommodationId)), [accommodations, accommodationId]);
    const selectedPickup = useMemo(() => pickUps.find(p => String(p.id) === String(pickupId)), [pickUps, pickupId]);

    // Combine insurances and supplements
    const allExtras = useMemo(() => [...insurances, ...supplements], [insurances, supplements]);
    const selectedExtras = useMemo(() => allExtras.filter(e => extrasIds.includes(String(e.id))), [allExtras, extrasIds]);

    // Split Insurance vs Other Extras (Supplements)
    const selectedInsuranceId = useMemo(() => {
        const insIds = insurances.map(i => String(i.id));
        const found = extrasIds.find(id => insIds.includes(String(id)));
        return found ? parseInt(found) : null;
    }, [insurances, extrasIds]);

    const selectedSupplementIds = useMemo(() => {
        const insIds = insurances.map(i => String(i.id));
        return extrasIds.filter(id => !insIds.includes(String(id)));
    }, [insurances, extrasIds]);

    // Calculate Prices
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

    // Helpers for rendering
    const schoolName = t(school.name, school.ar_name, isArabic);
    const location = t(school.city || school.location, school.city_ar, isArabic);
    const formattedStartDate = startDate
        ? new Date(startDate).toLocaleDateString(isArabic ? "ar-SA" : "en-GB", { day: "numeric", month: "long", year: "numeric" })
        : "-";

    const inWish = selectedCourse ? isInWishlist("language_courses", selectedCourse.id) : false;
    const inComp = selectedCourse ? isInCompare("language_courses", selectedCourse.id) : false;

    // -- Booking Logic --
    const [isVerificationOpen, setIsVerificationOpen] = useState(false);
    const [isSuccessModalOpen, setIsSuccessModalOpen] = useState(false);
    const [bookingId, setBookingId] = useState(null);
    const [submitting, setSubmitting] = useState(false);
    const [formData, setFormData] = useState({ name: "", email: "", phone: "" });
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [user, setUser] = useState(null);

    // Check Auth on Mount
    useEffect(() => {
        const checkAuth = async () => {
            const token = localStorage.getItem("auth_token");
            if (!token) return;

            try {
                const res = await fetch(buildApiUrl("/user"), {
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Accept": "application/json"
                    }
                });
                if (res.ok) {
                    const userData = await res.json();
                    setUser(userData);
                    setIsAuthenticated(true);
                    // Auto-fill form data for API payload usage
                    setFormData({
                        name: userData.name || "",
                        email: userData.email || "",
                        phone: userData.phone || userData.username || "",
                    });
                }
            } catch (e) {
                console.error("Auth check failed", e);
            }
        };
        checkAuth();
    }, []);

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleBookingSubmit = async (verificationToken = null) => {
        setSubmitting(true);
        try {
            const payload = {
                course_type: courseType,
                verification_token: verificationToken,
                user_data: { name: formData.name, email: formData.email },
                booking_data: {
                    course_id: courseId,
                    accommodation_id: accommodationId,
                    pickup_id: pickupId,
                    insurance_id: selectedInsuranceId,
                    weeks: weeks,
                    start_date: startDate,
                    final_price: totalPrice,
                    currency: currency,
                    supplements_ids: selectedSupplementIds,
                    user_age: accAge,
                    accommodation_weeks: weeks,
                }
            };

            const token = localStorage.getItem("auth_token");
            const headers = {
                "Content-Type": "application/json",
                "Accept": "application/json",
            };
            if (token) {
                headers["Authorization"] = `Bearer ${token}`;
            }

            let apiEndpoint = "/courseenglish/booking/language-course";
            if (courseType === "online_courses") apiEndpoint = "/courseenglish/booking/online-course";
            if (courseType === "summer_camps") apiEndpoint = "/courseenglish/booking/summer-camp";

            const res = await fetch(buildApiUrl(apiEndpoint), {
                method: "POST",
                headers: headers,
                body: JSON.stringify(payload),
            });

            const json = await res.json();
            if (!res.ok) throw new Error(json.message || "Booking failed");

            // Success!
            setBookingId(json.booking_id);
            setIsSuccessModalOpen(true);

            if (json.token) {
                localStorage.setItem("auth_token", json.token);
                // Trigger storage event for other tabs/components to update state
                window.dispatchEvent(new Event("storage"));
            }

        } catch (e) {
            alert(e.message);
        } finally {
            setSubmitting(false);
        }
    };

    const onSendRequest = (e) => {
        e && e.preventDefault();

        if (isAuthenticated) {
            handleBookingSubmit(null);
            return;
        }

        if (!formData.name || !formData.email || !formData.phone) {
            alert(isArabic ? "يرجى ملء جميع الحقول المطلوبة" : "Please fill in all required fields");
            return;
        }

        setIsVerificationOpen(true);
    };

    if (loading) return <div className="min-h-screen flex items-center justify-center text-slate-500 font-medium">Loading booking details...</div>;
    if (!slug || error) return <div className="min-h-screen flex items-center justify-center text-red-500 font-medium">Failed to load booking details. Please return to the previous page.</div>;

    return (
        <main className="min-h-screen bg-white lg:bg-[#FAFCFE] pb-24 pt-6" dir={isArabic ? "rtl" : "ltr"}>
            <div className="container mx-auto px-4">

                {/* Header Actions & Navigation */}
                <div className="mb-8 flex flex-col lg:flex-row items-center justify-between gap-4">
                    <div className="hidden lg:flex gap-6">
                        <button onClick={() => {
                            if (typeof window !== "undefined") {
                                const url = window.location.href;
                                navigator.clipboard.writeText(url).then(() => {
                                    alert(isArabic ? "تم نسخ الرابط" : "Link copied to clipboard");
                                });
                            }
                        }} className="flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-[#0057B7]">
                            <FontAwesomeIcon icon={faShare} />
                            <span>{l("share", isArabic)}</span>
                        </button>
                        {selectedCourse && (
                            <>
                                <button onClick={() => toggleWishlist("language_courses", selectedCourse.id)} className={`flex items-center gap-2 text-sm font-medium transition ${inWish ? "text-red-500" : "text-slate-600 hover:text-red-500"}`}>
                                    <FontAwesomeIcon icon={inWish ? faHeart : faHeart} />
                                    <span>{inWish ? l("inWishlist", isArabic) : l("addToWishlist", isArabic)}</span>
                                </button>
                                <button onClick={() => toggleCompare("language_courses", selectedCourse.id)} className={`flex items-center gap-2 text-sm font-medium transition ${inComp ? "text-[#0057B7]" : "text-slate-600 hover:text-[#0057B7]"}`}>
                                    <FontAwesomeIcon icon={faExchangeAlt} />
                                    <span>{inComp ? l("inCompare", isArabic) : l("addToCompare", isArabic)}</span>
                                </button>
                            </>
                        )}
                    </div>

                    <div className="w-full lg:w-auto text-center lg:text-end relative">
                        <Link href={`/language-institutes/${slug}`} className="lg:hidden absolute top-1 left-0 text-slate-600 p-2">
                            <Image src="/assets/icons/arrow-left.svg" width={20} height={20} alt="Back" className={isArabic ? "" : "rotate-180"} />
                        </Link>
                        <Link href={`/language-institutes/${slug}`} className="hidden lg:inline-flex items-center gap-2 text-sm font-medium text-slate-600 mb-4 transition hover:text-[#0057B7]">
                            <Image src="/assets/icons/arrow-left.svg" width={16} height={16} alt="Back" className={isArabic ? "" : "rotate-180"} />
                            <span>{l("backToList", isArabic)}</span>
                        </Link>
                        <h1 className="text-2xl lg:text-4xl font-semibold text-slate-900">{l("reviewConfirm", isArabic)}</h1>
                        <p className="mt-2 text-sm text-slate-500 hidden lg:block">{l("reviewSubtitle", isArabic)}</p>
                    </div>
                </div>

                {/* Main Content: 2-Column Layout (50/50 Split) */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                    {/* Contact Form or User Details Column */}
                    <div className="order-2 lg:order-1">
                        <div className="bg-[#F8FAFC] lg:bg-white rounded-[2rem] p-6 lg:p-10 lg:shadow-[0_2px_8px_rgba(0,0,0,0.02)] lg:border lg:border-white">
                            <h3 className="text-2xl font-semibold text-slate-900 mb-2 text-end">{l("contactDetails", isArabic)}</h3>

                            {isAuthenticated && user ? (
                                <div className="text-end">
                                    <p className="text-sm text-slate-500 mb-6">
                                        {isArabic ? "أنت مسجل الدخول كـ" : "You are logged in as"} <span className="font-medium text-slate-900">{user.name}</span>
                                    </p>

                                    <div className="space-y-4 bg-white p-6 rounded-xl border border-gray-100 mb-8">
                                        <div className="flex justify-between items-center border-b border-gray-50 pb-3">
                                            <span className="font-medium text-slate-900">{user.phone || user.username}</span>
                                            <span className="text-xs text-slate-400">{l("mobile", isArabic)}</span>
                                        </div>
                                        <div className="flex justify-between items-center border-b border-gray-50 pb-3">
                                            <span className="font-medium text-slate-900">{user.email}</span>
                                            <span className="text-xs text-slate-400">{l("email", isArabic)}</span>
                                        </div>
                                        <div className="flex justify-between items-center">
                                            <span className="font-medium text-slate-900">{user.name}</span>
                                            <span className="text-xs text-slate-400">{l("fullName", isArabic)}</span>
                                        </div>
                                    </div>

                                    <button
                                        onClick={() => onSendRequest()}
                                        disabled={submitting}
                                        className="w-full h-14 rounded-xl bg-[#0070CD] text-white font-medium text-lg hover:bg-[#005EB3] transition shadow-lg shadow-blue-100/50 disabled:opacity-50"
                                    >
                                        {submitting ? "Processing..." : (isArabic ? "تأكيد الحجز" : "Confirm Booking")}
                                    </button>
                                </div>
                            ) : (
                                <>
                                    <p className="text-sm text-slate-500 mb-8 text-end">{l("contactSubtitle", isArabic)}</p>
                                    <form className="space-y-6" onSubmit={onSendRequest}>
                                        <div>
                                            <label className="block text-sm font-medium text-slate-900 mb-2 text-end">
                                                <span className="text-red-500">*</span> {l("fullName", isArabic)}
                                            </label>
                                            <input
                                                type="text"
                                                name="name"
                                                value={formData.name}
                                                onChange={handleInputChange}
                                                placeholder={l("enterFullName", isArabic)}
                                                className="w-full h-12 rounded-xl border border-gray-200 px-4 text-sm outline-none focus:border-[#0057B7] text-end placeholder:text-gray-300"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-slate-900 mb-2 text-end">
                                                <span className="text-red-500">*</span> {l("email", isArabic)}
                                            </label>
                                            <input
                                                type="email"
                                                name="email"
                                                value={formData.email}
                                                onChange={handleInputChange}
                                                placeholder={l("enterEmail", isArabic)}
                                                className="w-full h-12 rounded-xl border border-gray-200 px-4 text-sm outline-none focus:border-[#0057B7] text-end placeholder:text-gray-300"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-slate-900 mb-2 text-end">
                                                <span className="text-red-500">*</span> {l("mobile", isArabic)}
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
                                                    className="flex-1 h-12 rounded-xl border border-gray-200 px-4 text-sm outline-none focus:border-[#0057B7] text-end placeholder:text-gray-300"
                                                />
                                            </div>
                                        </div>

                                        <button type="submit" disabled={submitting} className="w-full h-14 mt-6 rounded-xl bg-[#0070CD] text-white font-medium text-lg hover:bg-[#005EB3] transition shadow-lg shadow-blue-100/50 disabled:opacity-50">
                                            {submitting ? "Processing..." : l("sendRequest", isArabic)}
                                        </button>

                                        <div className="flex items-center justify-center gap-2 text-xs text-slate-400 mt-4">
                                            <FontAwesomeIcon icon={faCheckCircle} />
                                            <span>{l("disclaimer", isArabic)}</span>
                                        </div>
                                    </form>
                                </>
                            )}
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
                                    <Link href={`/language-institutes/${slug}`} className="bg-gray-50 text-slate-500 text-[10px] font-medium px-3 py-1 rounded hover:bg-gray-100 transition whitespace-nowrap">
                                        {l("change", isArabic)}
                                    </Link>
                                    <h3 className="font-medium text-slate-900 text-base leading-tight text-end flex-1 pl-3 dir-rtl">{schoolName}</h3>
                                </div>
                                <div className="flex items-center justify-end w-full gap-1 text-xs text-slate-400">
                                    <span>{location}</span>
                                </div>
                            </div>
                        </div>

                        {/* Price Summary */}
                        <div className="bg-white rounded-3xl border border-gray-100 p-6 shadow-[0_2px_12px_rgba(0,0,0,0.02)]">
                            <h3 className="font-semibold text-lg text-slate-900 mb-6 text-end">{l("priceSummary", isArabic)}</h3>

                            <div className="space-y-4">
                                {selectedCourse && (
                                    <div className="flex justify-between items-center text-sm group gap-6">
                                        <Price value={coursePrice} currency={currency} className="font-medium text-slate-900 tabular-nums shrink-0" />
                                        <span className="text-slate-600 font-normal text-end flex-1 ">{t(selectedCourse.name, selectedCourse.ar_name, isArabic)} ({weeks} {l("weeks", isArabic)})</span>
                                    </div>
                                )}

                                {selectedAccommodation && (
                                    <div className="flex justify-between items-center text-sm group gap-6">
                                        <Price value={accPrice} currency={currency} className="font-medium text-slate-900 tabular-nums shrink-0" />
                                        <span className="text-slate-600 font-normal text-end flex-1 ">{t(selectedAccommodation.title, selectedAccommodation.ar_title, isArabic)} ({weeks} {l("weeks", isArabic)})</span>
                                    </div>
                                )}

                                <div className="flex justify-between items-center text-sm group gap-6">
                                    <Price value={registrationFee} currency={currency} className="font-medium text-slate-900 tabular-nums shrink-0" />
                                    <span className="text-slate-600 font-normal text-end flex-1 ">{l("registrationFee", isArabic)}</span>
                                </div>

                                {selectedPickup && (
                                    <div className="flex justify-between items-center text-sm group gap-6">
                                        <Price value={pickupPrice} currency={currency} className="font-medium text-slate-900 tabular-nums shrink-0" />
                                        <span className="text-slate-600 font-normal text-end flex-1 ">{t(selectedPickup.route, selectedPickup.ar_route, isArabic)}</span>
                                    </div>
                                )}

                                {selectedExtras.map((ex, i) => (
                                    <div key={i} className="flex justify-between items-center text-sm group gap-6">
                                        <Price value={priceField(ex, "price", currency) || priceField(ex, "amount", currency)} currency={currency} className="font-medium text-slate-900 tabular-nums shrink-0" />
                                        <span className="text-slate-600 font-normal text-end flex-1 ">{t(ex.name, ex.ar_name, isArabic)}</span>
                                    </div>
                                ))}

                                {/* Discounts */}
                                {(discountPercent > 0 || discountAmount > 0) && (
                                    <>
                                        {bestDiscount > 0 && (
                                            <div className="flex justify-between items-center text-sm text-[#00C48C] group gap-6">
                                                <span className="font-medium block tabular-nums shrink-0" dir="ltr">-{bestDiscount}%</span>
                                                <span className="font-normal text-end flex-1 ">{l("discount", isArabic)}</span>
                                            </div>
                                        )}
                                        <div className="flex justify-between items-center text-sm text-[#00C48C] group gap-6">
                                            <Price value={discountAmount} currency={currency} isNegative={true} className="font-medium tabular-nums shrink-0" />
                                            <span className="font-normal text-end flex-1 ">{l("discount", isArabic)}</span>
                                        </div>
                                    </>
                                )}

                                <div className="border-t border-gray-100 my-4"></div>

                                <div className="flex justify-between items-center pt-2 gap-6">
                                    <Price value={totalPrice} currency={currency} size="lg" className="text-[#0B5DB6] font-semibold transform scale-105 tabular-nums shrink-0" />
                                    <div className="text-end">
                                        <span className="font-semibold text-slate-900 text-lg block">{l("total", isArabic)}</span>
                                        <span className="text-[10px] text-slate-400 block font-normal tracking-wide">{l("totalInclude", isArabic)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Details Cards */}
                        <div className="space-y-4">
                            {/* Course Details */}
                            <div className="bg-[#F8FAFC] rounded-2xl border border-gray-50 p-5">
                                <div className="flex justify-between items-center mb-4">
                                    <Link href={`/language-institutes/${slug}`} className="bg-white border border-gray-100 text-slate-500 text-[10px] font-medium px-3 py-1 rounded hover:bg-gray-50 transition shadow-sm">
                                        {l("change", isArabic)}
                                    </Link>
                                    <h4 className="font-medium text-slate-900 text-sm">{l("courseDetails", isArabic)}</h4>
                                </div>
                                <div className="text-end space-y-1">
                                    <p className="font-medium text-slate-800 text-sm">{selectedCourse ? t(selectedCourse.name, selectedCourse.ar_name, isArabic) : "-"}</p>
                                    <p className="text-xs text-slate-500 font-normal">{l("startDate", isArabic)}: {formattedStartDate}</p>
                                    <p className="text-xs text-slate-500 font-normal">{l("duration", isArabic)}: {weeks} {l("weeks", isArabic)}</p>
                                </div>
                            </div>

                            {/* Acc Details */}
                            {selectedAccommodation && (
                                <div className="bg-[#F8FAFC] rounded-2xl border border-gray-50 p-5">
                                    <div className="flex justify-between items-center mb-4">
                                        <Link href={`/language-institutes/${slug}`} className="bg-white border border-gray-100 text-slate-500 text-[10px] font-medium px-3 py-1 rounded hover:bg-gray-50 transition shadow-sm">
                                            {l("change", isArabic)}
                                        </Link>
                                        <h4 className="font-medium text-slate-900 text-sm">{l("accDetails", isArabic)}</h4>
                                    </div>
                                    <div className="text-end space-y-1">
                                        <p className="font-medium text-slate-800 text-sm">{t(selectedAccommodation.title, selectedAccommodation.ar_title, isArabic)}</p>
                                        <p className="text-xs text-slate-500 font-normal">{selectedAccommodation.features}</p>
                                    </div>
                                </div>
                            )}

                            {/* Extras Details */}
                            {(selectedPickup || selectedExtras.length > 0) && (
                                <div className="bg-[#F8FAFC] rounded-2xl border border-gray-50 p-5">
                                    <div className="flex justify-between items-center mb-4">
                                        <Link href={`/language-institutes/${slug}`} className="bg-white border border-gray-100 text-slate-500 text-[10px] font-medium px-3 py-1 rounded hover:bg-gray-50 transition shadow-sm">
                                            {l("change", isArabic)}
                                        </Link>
                                        <h4 className="font-medium text-slate-900 text-sm">{l("addServices", isArabic)}</h4>
                                    </div>
                                    <div className="space-y-3 text-end">
                                        {selectedPickup && (
                                            <div className="flex items-center justify-end gap-2">
                                                <div className="text-end">
                                                    <p className="font-medium text-slate-900 text-sm">{l("pickup", isArabic)}</p>
                                                    <p className="text-xs text-slate-500 font-normal">{t(selectedPickup.route, selectedPickup.ar_route, isArabic)}</p>
                                                </div>
                                                <div className="w-5 h-5 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-[10px]">
                                                    <FontAwesomeIcon icon={faCheckCircle} />
                                                </div>
                                            </div>
                                        )}
                                        {selectedExtras.map((ex, i) => (
                                            <div key={i} className="flex items-center justify-end gap-2">
                                                <div className="text-end">
                                                    <p className="font-medium text-slate-900 text-sm">{t(ex.name, ex.ar_name, isArabic)}</p>
                                                </div>
                                                <div className="w-5 h-5 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-[10px]">
                                                    <FontAwesomeIcon icon={faCheckCircle} />
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                        </div>

                    </div>
                </div>

                <BookingVerificationModal
                    isOpen={isVerificationOpen}
                    onClose={() => setIsVerificationOpen(false)}
                    phone={formData.phone}
                    onVerified={(token) => handleBookingSubmit(token)}
                />

                <BookingSuccessModal
                    isOpen={isSuccessModalOpen}
                    onClose={() => {
                        setIsSuccessModalOpen(false);
                        window.location.href = "/dashboard";
                    }}
                    bookingId={bookingId}
                    isArabic={isArabic}
                />

            </div>
        </main>
    );
}

export default function BookingConfirmationPage() {
    return (
        <Suspense fallback={<div className="min-h-screen flex items-center justify-center font-medium">Loading...</div>}>
            <BookingConfirmationContent />
        </Suspense>
    );
}
