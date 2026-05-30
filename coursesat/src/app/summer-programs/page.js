import { headers } from "next/headers";
import { isMobileRequest } from "@/lib/device";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import MobileHeader from "@/components/mobile/mobile-header";
import MobileFooter from "@/components/mobile/mobile-footer";
import MobileBottomNav from "@/components/mobile/mobile-bottom-nav";
import SummerCampsBlocked from "@/components/shared/summer-camps-blocked";

export default async function SummerProgramsPage() {
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";
  const isMobile = isMobileRequest(userAgent);

  if (isMobile) {
    return (
      <div className="flex min-h-screen flex-col pb-[88px]">
        <MobileHeader />
        <main className="flex flex-1 flex-col">
          <SummerCampsBlocked />
        </main>
        <MobileFooter />
        <MobileBottomNav />
      </div>
    );
  }

  return (
    <div className="flex min-h-screen flex-col">
      <DesktopHeader />
      <main className="flex flex-1 flex-col">
        <SummerCampsBlocked />
      </main>
      <DesktopFooter />
    </div>
  );
}
