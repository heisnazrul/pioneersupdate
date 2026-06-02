"use client";

import { useEffect, useMemo, useState } from "react";
import { useSearchParams } from "next/navigation";
import { useCurrency } from "@/components/providers/currency-provider";
import { useApi } from "@/lib/api";
import { computeInstitutePricing } from "@/lib/institute-pricing";
import { resolveCoursePromotionPercent } from "@/lib/pioneers-discount";
import { getStoredReferral } from "@/lib/referral";
import {
    buildInstituteBookingCheckoutUrl,
    buildInstituteDetailsUrl,
    buildInstituteBookingUrl,
    formatInstituteQueryDate,
    readInstituteSelectionFromSearchParams,
} from "@/lib/institute-booking-url";
import { accommodationFeatureLine, formatDisplayDate } from "@/components/mobile/institute-booking-ui";

export function useInstituteBooking(slug, { t, isArabic }) {
    const searchParams = useSearchParams();
    const { currency, activeCurrency } = useCurrency();
    const [appliedReferral, setAppliedReferral] = useState(null);

    useEffect(() => {
        const stored = getStoredReferral();
        if (stored?.code) setAppliedReferral(stored);
    }, []);

    const selection = useMemo(
        () => readInstituteSelectionFromSearchParams(searchParams),
        [searchParams],
    );

    const apiPath = slug
        ? `/coursesat/language-institutes/${encodeURIComponent(slug)}${searchParams.toString() ? `?${searchParams.toString()}` : ""}`
        : null;
    const { data: instituteData, loading } = useApi(apiPath);

    const l = (key) => t(`pages.institute_details.booking.${key}`) || t(`pages.institute_details.${key}`);
    const loc = (en, ar) => ((isArabic && ar) ? ar : en);

    const detailsUrl = buildInstituteDetailsUrl(slug, {
        courseId: selection.courseId,
        weeks: selection.weeks,
        accommodationId: selection.accommodationId,
        pickupId: selection.pickupId,
        startDate: formatInstituteQueryDate(selection.startDate),
        extras: selection.extras,
        accAge: selection.accAge,
    });

    const selectionQuery = {
        courseId: selection.courseId,
        weeks: selection.weeks,
        accommodationId: selection.accommodationId,
        pickupId: selection.pickupId,
        startDate: formatInstituteQueryDate(selection.startDate),
        extras: selection.extras,
        accAge: selection.accAge,
    };
    const reviewUrl = buildInstituteBookingUrl(slug, selectionQuery);
    const checkoutUrl = buildInstituteBookingCheckoutUrl(slug, selectionQuery);

    const school = instituteData?.school;
    const courses = instituteData?.courses || [];
    const accommodations = instituteData?.accommodations || [];
    const pickUps = instituteData?.pickups || [];
    const insurances = instituteData?.insurances || [];
    const supplements = instituteData?.supplements || [];
    const regFeeObj = instituteData?.registration_fee;
    const discounts = instituteData?.discounts || [];
    const pioneersDiscounts = instituteData?.pioneers_discounts || [];

    const selectedCourse = courses.find((course) => Number(course.id) === Number(selection.courseId))
        || courses[0];
    const selectedCourseId = selectedCourse?.id ?? selection.courseId;
    const selectedAccommodation = selection.accommodationId && selection.accommodationId !== "no-acc"
        ? accommodations.find((item) => String(item.id) === String(selection.accommodationId))
        : null;
    const selectedPickup = pickUps.find((item) => Number(item.id) === Number(selection.pickupId));
    const selectedInsurances = insurances.filter(
        (ins) => ins.is_mandatory || selection.extras.includes(ins.id),
    );
    const selectedSupplements = supplements.filter((supp) => selection.extras.includes(supp.id));

    const courseDiscountPercent = resolveCoursePromotionPercent(
        selectedCourseId,
        discounts,
        selectedCourse,
    );
    const referralDiscountPercent = appliedReferral?.discount_percent
        ? Number(appliedReferral.discount_percent)
        : 0;

    const pricing = computeInstitutePricing({
        selectedCourse,
        selectedAccommodation,
        selectedPickup,
        selectedInsurances,
        selectedSupplements,
        weeks: selection.weeks,
        startDate: selection.startDate,
        accAge: selection.accAge,
        currency,
        registrationFeeObj: regFeeObj,
        courseDiscountPercent,
        referralDiscountPercent,
        pioneersDiscounts,
        supplementLabels: {
            material_books: l("materialBooksFee") || t("pages.institute_details.materialBooksFee"),
            registration: l("registrationFee"),
            mandatory: l("mandatoryFee") || t("pages.institute_details.mandatoryFee"),
            summer: l("summerSupplement") || t("pages.institute_details.summerSupplement"),
            winter: l("winterSupplement") || t("pages.institute_details.winterSupplement"),
            other: l("otherSupplement") || t("pages.institute_details.otherSupplement"),
            under_18: l("under18Supplement") || t("pages.institute_details.under18Supplement"),
            insurance: l("insurance") || t("pages.institute_details.step3insurance"),
            insurance_admin: l("insuranceAdminFee") || t("pages.institute_details.insuranceAdminFee"),
        },
    });

    const totalDiscountAmount = pricing.courseDiscountAmount
        + pricing.referralDiscountAmount
        + pricing.pioneersCashTotal;

    const schoolName = loc(school?.name, school?.ar_name);
    const location = loc(school?.location, school?.location_ar)
        || [loc(school?.country || school?.country_name, school?.country_ar || school?.country_ar_name), loc(school?.city, school?.city_ar)]
            .filter(Boolean)
            .join(isArabic ? " ، " : ", ");
    const formattedStartDate = formatDisplayDate(selection.startDate, isArabic);
    const accFeatureSubtitle = selectedAccommodation ? accommodationFeatureLine(selectedAccommodation) : null;

    return {
        selection,
        currency,
        activeCurrency,
        instituteData,
        loading,
        school,
        pickUps,
        detailsUrl,
        reviewUrl,
        checkoutUrl,
        l,
        loc,
        selectedCourse,
        selectedCourseId,
        selectedAccommodation,
        selectedPickup,
        selectedInsurances,
        selectedSupplements,
        appliedReferral,
        referralDiscountPercent,
        pricing,
        totalDiscountAmount,
        schoolName,
        location,
        formattedStartDate,
        accFeatureSubtitle,
    };
}
