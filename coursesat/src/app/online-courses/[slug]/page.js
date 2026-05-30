import { Suspense } from "react";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import DesktopOnlineCourseDetails from "@/components/desktop/desktop-online-course-details";

function DetailsFallback() {
  return (
    <div className="flex min-h-[50vh] items-center justify-center">
      <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
    </div>
  );
}

export default async function OnlineCourseDetailsPage({ params }) {
  const { slug } = await params;

  return (
    <main className="min-h-screen bg-white lg:bg-[#F8FAFC]">
      <div className="hidden md:block">
        <DesktopHeader />
        <Suspense fallback={<DetailsFallback />}>
          <DesktopOnlineCourseDetails slug={slug} />
        </Suspense>
        <DesktopFooter />
      </div>
      <div className="md:hidden">
        <Suspense fallback={<DetailsFallback />}>
          <DesktopOnlineCourseDetails slug={slug} />
        </Suspense>
      </div>
    </main>
  );
}
