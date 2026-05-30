import { headers } from "next/headers";
import { redirect } from "next/navigation";
import { isMobileRequest } from "@/lib/device";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import MobileHeader from "@/components/mobile/mobile-header";
import MobileFooter from "@/components/mobile/mobile-footer";
import MobileBottomNav from "@/components/mobile/mobile-bottom-nav";
import ComparePage from "@/components/compare/compare-page";

export const metadata = {
  title: "Compare Courses | CourseSat",
  description: "Compare your selected language and online courses side by side.",
};

export default async function CompareRoutePage() {
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";
  const isMobile = isMobileRequest(userAgent);

  if (isMobile) {
    return (
      <div className="flex min-h-screen flex-col pb-[88px]">
        <MobileHeader />
        <main className="flex-1 bg-[#F8FAFC]">
          <ComparePage />
        </main>
        <MobileFooter />
        <MobileBottomNav />
      </div>
    );
  }

  return (
    <div className="flex min-h-screen flex-col bg-[#F8FAFC]">
      <DesktopHeader />
      <main className="flex-1">
        <ComparePage />
      </main>
      <DesktopFooter />
    </div>
  );
}
