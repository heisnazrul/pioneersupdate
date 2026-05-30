import { Suspense } from "react";
import DesktopInstituteDetails from "@/components/desktop/desktop-institute-details";
import MobileInstituteDetails from "@/components/mobile/mobile-institute-details";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";

function DetailsFallback() {
  return (
    <div className="flex min-h-[50vh] items-center justify-center">
      <div className="h-10 w-10 animate-spin rounded-full border-4 border-[#0057B7] border-t-transparent" />
    </div>
  );
}

export default async function InstituteDetailsPage({ params }) {
  const { slug } = await params;

  return (
    <main className="min-h-screen bg-white lg:bg-[#F8FAFC]">
      <div className="hidden md:block">
        <DesktopHeader />
        <Suspense fallback={<DetailsFallback />}>
          <DesktopInstituteDetails slug={slug} />
        </Suspense>
        <DesktopFooter />
      </div>
      <div className="md:hidden">
        <Suspense fallback={<DetailsFallback />}>
          <MobileInstituteDetails slug={slug} />
        </Suspense>
      </div>
    </main>
  );
}
