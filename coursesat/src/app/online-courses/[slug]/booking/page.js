import { Suspense } from "react";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import DesktopOnlineCourseBooking from "@/components/desktop/desktop-online-course-booking";

function BookingFallback() {
  return (
    <div className="flex min-h-[50vh] items-center justify-center">
      <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
    </div>
  );
}

export default async function OnlineCourseBookingPage({ params }) {
  const { slug } = await params;

  return (
    <main className="min-h-screen bg-white lg:bg-[#FAFCFE]">
      <div className="hidden md:block">
        <DesktopHeader />
        <Suspense fallback={<BookingFallback />}>
          <DesktopOnlineCourseBooking slug={slug} />
        </Suspense>
        <DesktopFooter />
      </div>
      <div className="md:hidden">
        <Suspense fallback={<BookingFallback />}>
          <DesktopOnlineCourseBooking slug={slug} />
        </Suspense>
      </div>
    </main>
  );
}
