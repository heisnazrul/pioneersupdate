"use client";

import { Suspense, useEffect } from "react";
import { useSearchParams } from "next/navigation";
import { captureReferralFromUrl } from "@/lib/referral";

function ReferralCaptureInner() {
  const searchParams = useSearchParams();

  useEffect(() => {
    const ref = searchParams.get("ref") || searchParams.get("referral");
    if (!ref) return;
    captureReferralFromUrl(searchParams);
  }, [searchParams]);

  return null;
}

export default function ReferralCapture() {
  return (
    <Suspense fallback={null}>
      <ReferralCaptureInner />
    </Suspense>
  );
}
