"use client";

import { Suspense, useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";

function RedirectToStandaloneConfirmation() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const reference = searchParams.get("ref") || searchParams.get("booked") || "";

  useEffect(() => {
    if (reference) {
      router.replace(`/booking/confirmation?ref=${encodeURIComponent(reference)}`);
      return;
    }
    router.replace("/student/bookings");
  }, [reference, router]);

  return (
    <div className="rounded-3xl border border-slate-200 bg-white p-10 text-center text-slate-500">
      ...
    </div>
  );
}

export default function StudentBookingConfirmationRedirect() {
  return (
    <Suspense fallback={<div className="rounded-3xl border border-slate-200 bg-white p-10 text-center text-slate-500">...</div>}>
      <RedirectToStandaloneConfirmation />
    </Suspense>
  );
}
