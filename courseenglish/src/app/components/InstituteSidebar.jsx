"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShare } from "@fortawesome/free-solid-svg-icons";
import BookingSummary from "@/app/components/BookingSummary";

export default function InstituteSidebar({
    institute,
    selectedCourse,
    selectedAccommodation,
    selectedPickup,
    selectedExtras,
    weeks,
    startDate,
    onDateChange,
    onWeeksChange,
    currency = "GBP",
    isArabic = false,
    registrationFee = 50,
    discountPercent = 0,
    slug,
    accAge,
}) {
    return (
        <div className="sticky top-4 space-y-6">
            <BookingSummary
                institute={institute}
                selectedCourse={selectedCourse}
                selectedAccommodation={selectedAccommodation}
                selectedPickup={selectedPickup}
                selectedExtras={selectedExtras}
                weeks={weeks}
                title={institute?.name || "Institute"}
                slug={slug}
                startDate={startDate}
                onDateChange={onDateChange}
                onWeeksChange={onWeeksChange}
                currency={currency}
                isArabic={isArabic}
                registrationFee={registrationFee}
                discountPercent={discountPercent}
                accAge={accAge}
            />

            {/* Inquiry Section */}
            <div className="rounded-2xl border border-gray-200 bg-[#F0FDF4] p-6 text-center shadow-sm">
                <div className="flex justify-center mb-3">
                    <div className="h-12 w-12 rounded-full bg-[#E3FCEF] flex items-center justify-center text-[#25D366]">
                        <FontAwesomeIcon icon={faShare} className="text-xl" />
                    </div>
                </div>
                <h4 className="text-lg font-semibold text-slate-900 mb-2">
                    {isArabic ? "هل لديك سؤال؟" : "Have a Question?"}
                </h4>
                <p className="mb-4 text-sm text-slate-600 leading-relaxed">
                    {isArabic
                        ? "هل لديك أسئلة أو تحتاج مزيداً من المعلومات حول هذه الدورة؟"
                        : "Do you have questions or need more info about this course?"}
                </p>
                <button className="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-[#25D366] px-6 py-3 font-medium text-white transition hover:bg-[#128C7E] shadow-lg shadow-green-200">
                    <FontAwesomeIcon icon={faShare} />
                    {isArabic ? "تواصل عبر واتساب" : "Chat via WhatsApp"}
                </button>
            </div>
        </div>
    );
}
