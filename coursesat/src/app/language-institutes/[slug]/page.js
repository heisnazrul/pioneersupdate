import { Suspense } from "react";
import { headers } from "next/headers";
import { isMobileRequest } from "@/lib/device";
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
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";
  const isMobile = isMobileRequest(userAgent);

  if (isMobile) {
    const MobileInstituteDetails = (
      await import("@/components/mobile/mobile-institute-details")
    ).default;

    return (
      <main className="min-h-screen bg-white">
        <Suspense fallback={<DetailsFallback />}>
          <MobileInstituteDetails slug={slug} />
        </Suspense>
      </main>
    );
  }

  const DesktopInstituteDetails = (
    await import("@/components/desktop/desktop-institute-details")
  ).default;

  return (
    <main className="min-h-screen bg-white lg:bg-[#F8FAFC]">
      <DesktopHeader />
      <Suspense fallback={<DetailsFallback />}>
        <DesktopInstituteDetails slug={slug} />
      </Suspense>
      <DesktopFooter />
    </main>
  );
}
