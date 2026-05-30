"use client";

import { Suspense, useEffect, useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";

import BookingConfirmation from "@/components/student/booking-confirmation";
import { resolveBookingDetail } from "@/lib/language-course-booking-api";

function BookingConfirmationLoader() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const reference = searchParams.get("ref") || searchParams.get("booked") || "";
  const [booking, setBooking] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    if (!reference) {
      router.replace("/language-institutes");
      return undefined;
    }

    let active = true;

    resolveBookingDetail(reference)
      .then((data) => {
        if (!active) return;
        if (!data) {
          setError("Booking not found.");
          return;
        }
        setBooking(data);
      })
      .catch((err) => {
        if (!active) return;
        setError(err?.message || "Failed to load booking.");
      })
      .finally(() => {
        if (active) setLoading(false);
      });

    return () => {
      active = false;
    };
  }, [reference, router]);

  if (loading) {
    return (
      <div className="flex min-h-[50vh] items-center justify-center px-4 py-16">
        <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0B5DB6] border-t-transparent" />
      </div>
    );
  }

  if (error || !booking) {
    return (
      <div className="mx-auto max-w-lg px-4 py-16">
        <div className="rounded-3xl border border-red-200 bg-red-50 p-10 text-center text-red-700">
          {error || "Booking not found."}
        </div>
      </div>
    );
  }

  return <BookingConfirmation booking={booking} />;
}

export default function BookingConfirmationPageInner() {
  return (
    <Suspense
      fallback={
        <div className="flex min-h-[50vh] items-center justify-center px-4 py-16">
          <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0B5DB6] border-t-transparent" />
        </div>
      }
    >
      <BookingConfirmationLoader />
    </Suspense>
  );
}
