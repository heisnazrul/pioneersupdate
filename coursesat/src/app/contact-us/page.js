import { headers } from "next/headers";
import { isMobileRequest } from "@/lib/device";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import MobileHeader from "@/components/mobile/mobile-header";
import MobileFooter from "@/components/mobile/mobile-footer";
import MobileBottomNav from "@/components/mobile/mobile-bottom-nav";

export default async function ContactUsPage() {
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";
  const isMobile = isMobileRequest(userAgent);

  if (isMobile) {
    const MobileContact = (await import("@/components/mobile/mobile-contact")).default;
    return (
      <div className="flex min-h-screen flex-col pb-[88px]">
        <MobileHeader />
        <main className="flex-1">
          <MobileContact />
        </main>
        <MobileFooter />
        <MobileBottomNav />
      </div>
    );
  }

  const DesktopContact = (await import("@/components/desktop/desktop-contact")).default;
  return (
    <div className="flex min-h-screen flex-col">
      <DesktopHeader />
      <main className="flex-1">
        <DesktopContact />
      </main>
      <DesktopFooter />
    </div>
  );
}
