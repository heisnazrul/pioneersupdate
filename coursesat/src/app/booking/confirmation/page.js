import { Suspense } from "react";

import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import BookingConfirmationPageInner from "@/components/student/booking-confirmation-page";

function ConfirmationFallback() {
  return (
    <div className="flex min-h-[50vh] items-center justify-center bg-[#F0F7FC]">
      <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0B5DB6] border-t-transparent" />
    </div>
  );
}

export default function BookingConfirmationRoute() {
  return (
    <main className="min-h-screen bg-[#F0F7FC]">
      <div className="hidden md:block">
        <DesktopHeader />
      </div>
      <Suspense fallback={<ConfirmationFallback />}>
        <BookingConfirmationPageInner />
      </Suspense>
      <div className="hidden md:block">
        <DesktopFooter />
      </div>
    </main>
  );
}
