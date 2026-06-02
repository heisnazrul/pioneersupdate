"use client";

import Link from "next/link";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheckCircle } from "@fortawesome/free-solid-svg-icons";
import { useLocale } from "@/components/providers/locale-provider";
import { useInstituteBooking } from "@/hooks/use-institute-booking";
import {
    BookingBackLink,
    CardSectionHeader,
    InstituteBookingPriceSummary,
    SummaryServiceRow,
} from "@/components/mobile/institute-booking-ui";

export default function MobileInstituteBooking({ slug }) {
    const { language, direction, t } = useLocale();
    const isArabic = language === "ar";
    const isRtl = direction === "rtl";
    const textAlign = isRtl ? "text-right" : "text-left";

    const {
        loading,
        school,
        detailsUrl,
        checkoutUrl,
        l,
        loc,
        selection,
        currency,
        activeCurrency,
        selectedCourse,
        selectedAccommodation,
        selectedPickup,
        selectedInsurances,
        selectedSupplements,
        appliedReferral,
        referralDiscountPercent,
        pricing,
        schoolName,
        location,
        formattedStartDate,
        accFeatureSubtitle,
    } = useInstituteBooking(slug, { t, isArabic });

    if (loading && !school) {
        return (
            <div className="flex min-h-[50vh] items-center justify-center bg-[#F8FAFC]">
                <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
            </div>
        );
    }

    if (!loading && !school) {
        return (
            <div className="flex min-h-[50vh] flex-col items-center justify-center bg-[#F8FAFC] px-4 text-center">
                <p className="text-lg text-slate-600">{isArabic ? "المعهد غير موجود" : "Institute not found"}</p>
                <Link href="/language-institutes" className="mt-4 text-[#0057B7] hover:underline">
                    {isArabic ? "العودة إلى المعاهد" : "Back to institutes"}
                </Link>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-[#F8FAFC] pb-28" dir={direction}>
            <div className="border-b border-[#E8ECF1] bg-white px-4 pb-4 pt-5">
                <BookingBackLink href={detailsUrl} label={l("backToEdit")} isRtl={isRtl} />
                <h1 className={`text-xl font-bold text-[#102233] ${textAlign}`}>{l("reviewBooking")}</h1>
                <p className={`mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-[#10B981] ${textAlign}`}>
                    <FontAwesomeIcon icon={faCheckCircle} className="h-3.5 w-3.5" />
                    <span>{l("progressSaved")}</span>
                </p>
            </div>

            <div className="space-y-4 px-4 pt-4">
                {/* Institute card */}
                <div className="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div className="flex items-start gap-3 p-4" dir="ltr">
                        {isRtl ? (
                            <>
                                <Link
                                    href={detailsUrl}
                                    className="shrink-0 rounded border border-gray-100 bg-white px-2.5 py-1 text-[10px] font-medium text-[#0057B7]"
                                >
                                    {l("change")}
                                </Link>
                                <div className="min-w-0 flex-1 text-right">
                                    <h3 className="text-base font-semibold leading-tight text-[#102233]">{schoolName}</h3>
                                    <p className="mt-1 text-xs text-slate-500">{location}</p>
                                </div>
                                <div className="relative h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-gray-50">
                                    <img src={school?.image || "/assets/hero.png"} alt={schoolName} className="h-full w-full object-cover" />
                                </div>
                            </>
                        ) : (
                            <>
                                <div className="relative h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-gray-50">
                                    <img src={school?.image || "/assets/hero.png"} alt={schoolName} className="h-full w-full object-cover" />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <h3 className="text-base font-semibold leading-tight text-[#102233]">{schoolName}</h3>
                                    <p className="mt-1 text-xs text-slate-500">{location}</p>
                                </div>
                                <Link
                                    href={detailsUrl}
                                    className="shrink-0 rounded border border-gray-100 bg-white px-2.5 py-1 text-[10px] font-medium text-[#0057B7]"
                                >
                                    {l("change")}
                                </Link>
                            </>
                        )}
                    </div>
                </div>

                {/* Course details */}
                <div className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <CardSectionHeader
                        title={l("courseDetails")}
                        changeHref={detailsUrl}
                        changeLabel={l("edit")}
                        isRtl={isRtl}
                    />
                    <div className={`space-y-1 ${textAlign}`}>
                        <p className="text-sm font-medium text-[#102233]">
                            {selectedCourse ? loc(selectedCourse.name, selectedCourse.ar_name) : "-"}
                        </p>
                        <p className="text-xs text-slate-500">
                            {t("pages.institute_details.startDate")}: {formattedStartDate}
                        </p>
                        <p className="text-xs text-slate-500">
                            {l("duration")}: {selection.weeks} {l("weeks")}
                        </p>
                    </div>
                </div>

                {selectedAccommodation && (
                    <div className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <CardSectionHeader
                            title={l("accDetails")}
                            changeHref={detailsUrl}
                            changeLabel={l("edit")}
                            isRtl={isRtl}
                        />
                        <div className={`space-y-1 ${textAlign}`}>
                            <p className="text-sm font-medium text-[#102233]">
                                {loc(selectedAccommodation.title || selectedAccommodation.name, selectedAccommodation.ar_title || selectedAccommodation.ar_name)}
                            </p>
                            {accFeatureSubtitle && (
                                <p className="text-xs text-slate-500">{accFeatureSubtitle}</p>
                            )}
                        </div>
                    </div>
                )}

                {/* Additional services — always shown per Figma */}
                <div className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <CardSectionHeader
                        title={l("addServices")}
                        changeHref={detailsUrl}
                        changeLabel={l("edit")}
                        isRtl={isRtl}
                    />
                    <div className="space-y-3">
                        {selectedPickup ? (
                            <SummaryServiceRow
                                isRtl={isRtl}
                                title={l("pickup")}
                                subtitle={loc(selectedPickup.name || selectedPickup.route, selectedPickup.ar_name || selectedPickup.ar_route)}
                            />
                        ) : (
                            <SummaryServiceRow
                                isRtl={isRtl}
                                title={l("pickup")}
                                subtitle={isArabic ? "بدون استقبال" : "No pickup"}
                            />
                        )}
                        {selectedInsurances.map((item) => (
                            <SummaryServiceRow
                                key={`ins-${item.id}`}
                                isRtl={isRtl}
                                title={loc(item.name, item.ar_name)}
                            />
                        ))}
                        {selectedSupplements.map((item) => (
                            <SummaryServiceRow
                                key={`supp-${item.id}`}
                                isRtl={isRtl}
                                title={loc(item.name, item.ar_name)}
                            />
                        ))}
                    </div>
                </div>

                {/* Price summary — last before submit */}
                <InstituteBookingPriceSummary
                    l={l}
                    loc={loc}
                    isRtl={isRtl}
                    textAlign={textAlign}
                    currency={currency}
                    activeCurrency={activeCurrency}
                    selectedCourse={selectedCourse}
                    selectedAccommodation={selectedAccommodation}
                    selectedPickup={selectedPickup}
                    selection={selection}
                    pricing={pricing}
                    appliedReferral={appliedReferral}
                    referralDiscountPercent={referralDiscountPercent}
                />
            </div>

            <div className="fixed bottom-0 left-0 z-30 w-full border-t border-[#E8ECF1] bg-white px-4 py-4">
                <Link
                    href={checkoutUrl}
                    className="flex h-12 w-full items-center justify-center rounded-2xl bg-[#0070CD] text-[15px] font-semibold text-white transition hover:bg-[#005EB3]"
                >
                    {l("sendRequest")}
                </Link>
            </div>
        </div>
    );
}
