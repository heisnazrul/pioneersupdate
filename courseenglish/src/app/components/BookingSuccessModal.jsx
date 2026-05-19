"use client";

import { useMemo } from "react";

import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheckCircle, faTimes, faChartLine } from "@fortawesome/free-solid-svg-icons";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";
import Image from "next/image";

export default function BookingSuccessModal({ isOpen, onClose, bookingId, isArabic, userPhone }) {
    if (!isOpen) return null;

    // Format Booking ID: LG-{3 random digits}{BookingID padded to 3 digits}
    // Example: ID 4 -> LG-123004
    const formattedId = useMemo(() => {
        if (!bookingId) return '---';
        const randomPrefix = Math.floor(100 + Math.random() * 900); // 3 random digits
        const idPart = String(bookingId).padStart(3, '0');
        return `LG-${randomPrefix}${idPart}`;
    }, [bookingId]);

    // Default Support WhatsApp
    const supportPhone = "966500000000"; // Replace with actual support number if known, or use dynamic
    const whatsappUrl = `https://wa.me/${supportPhone}?text=${encodeURIComponent(
        isArabic
            ? `مرحبا، قمت بالحجز برقم ${formattedId} وأود استكمال الإجراءات.`
            : `Hello, I made a booking #${formattedId} and would like to proceed.`
    )}`;

    return (
        <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-in fade-in duration-200">
            <div className="relative w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl animate-in zoom-in-95 duration-200">

                {/* Close Button */}
                <button
                    onClick={onClose}
                    className="absolute right-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200 hover:text-slate-900"
                >
                    <FontAwesomeIcon icon={faTimes} className="h-4 w-4" />
                </button>

                <div className="flex flex-col items-center p-8 text-center">
                    {/* Success Icon */}
                    <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-green-600 ring-8 ring-green-50">
                        <FontAwesomeIcon icon={faCheckCircle} className="h-10 w-10" />
                    </div>

                    {/* Title */}
                    <h2 className="mb-2 text-2xl font-semibold text-slate-900">
                        {isArabic ? "تم استلام طلبك بنجاح!" : "Booking Received!"}
                    </h2>

                    <p className="mb-6 text-slate-600">
                        {isArabic
                            ? "شكراً لك، تم تسجيل طلبك المبدئي بنجاح."
                            : "Thank you, your preliminary booking has been received."}
                    </p>

                    {/* Booking Reference */}
                    <div className="mb-8 w-full rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <div className="text-xs font-medium uppercase tracking-wider text-slate-400 mb-1">
                            {isArabic ? "رقم الحجز" : "Booking Reference"}
                        </div>
                        <div className="flex items-center justify-center gap-2">
                            <span className="text-3xl font-semibold text-[#0B5DB6] tracking-tight">{formattedId}</span>
                            {/* Copy Icon could go here */}
                        </div>
                    </div>

                    {/* Action Button */}
                    <a
                        href={whatsappUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="group relative flex w-full items-center justify-center gap-3 rounded-xl bg-[#25D366] px-6 py-4 font-medium text-white transition-all hover:bg-[#20bd5a] hover:shadow-lg hover:shadow-green-500/30 active:scale-[0.98]"
                    >
                        <FontAwesomeIcon icon={faWhatsapp} className="h-6 w-6" />
                        <span>
                            {isArabic ? "تواصل معنا عبر واتساب" : "Contact via WhatsApp"}
                        </span>
                    </a>

                    <Link href="/dashboard" className="group mt-4 flex w-full items-center justify-center gap-3 rounded-xl border-2 border-slate-100 bg-white px-6 py-4 font-medium text-slate-600 transition-all hover:border-[#0057B7] hover:text-[#0057B7] active:scale-[0.98]">
                        <FontAwesomeIcon icon={faChartLine} className="h-5 w-5" />
                        <span>{isArabic ? "متابعة الطلب في لوحة التحكم" : "Track in Dashboard"}</span>
                    </Link>

                    <p className="mt-4 text-xs text-slate-400">
                        {isArabic
                            ? "سيتواصل معك أحد مستشارينا قريباً لتأكيد الحجز."
                            : "One of our consultants will contact you shortly to confirm."}
                    </p>
                </div>
            </div>
        </div>
    );
}
