import { headers } from "next/headers";
import { isMobileRequest } from "@/lib/device";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import MobileHeader from "@/components/mobile/mobile-header";
import MobileFooter from "@/components/mobile/mobile-footer";
import MobileBottomNav from "@/components/mobile/mobile-bottom-nav";

export default async function ArticlesPage() {
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";
  const isMobile = isMobileRequest(userAgent);

  if (isMobile) {
    const MobileArticles = (await import("@/components/mobile/mobile-articles")).default;
    return (
      <div className="flex min-h-screen flex-col pb-[88px] bg-slate-50">
        <MobileHeader />
        <main className="flex-1">
          <MobileArticles />
        </main>
        <MobileFooter />
        <MobileBottomNav />
      </div>
    );
  }

  const DesktopArticles = (await import("@/components/desktop/desktop-articles")).default;
  return (
    <div className="flex min-h-screen flex-col bg-slate-50">
      <DesktopHeader />
      <main className="flex-1">
        <DesktopArticles />
      </main>
      <DesktopFooter />
    </div>
  );
}
