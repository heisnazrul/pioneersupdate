import { headers } from "next/headers";
import { isMobileRequest } from "@/lib/device";
import DesktopHeader from "@/components/desktop/desktop-header";
import DesktopFooter from "@/components/desktop/desktop-footer";
import MobileHeader from "@/components/mobile/mobile-header";
import MobileFooter from "@/components/mobile/mobile-footer";
import MobileBottomNav from "@/components/mobile/mobile-bottom-nav";

export default async function ArticleDetailsPage({ params }) {
  const { slug } = await params;
  const headerStore = await headers();
  const userAgent = headerStore.get("user-agent") ?? "";
  const isMobile = isMobileRequest(userAgent);

  if (isMobile) {
    const MobileArticleDetails = (await import("@/components/mobile/mobile-article-details")).default;
    return (
      <div className="flex min-h-screen flex-col pb-[88px] bg-slate-50">
        <MobileHeader />
        <main className="flex-1">
          <MobileArticleDetails slug={slug} />
        </main>
        <MobileFooter />
        <MobileBottomNav />
      </div>
    );
  }

  const DesktopArticleDetails = (await import("@/components/desktop/desktop-article-details")).default;
  return (
    <div className="flex min-h-screen flex-col bg-slate-50">
      <DesktopHeader />
      <main className="flex-1">
        <DesktopArticleDetails slug={slug} />
      </main>
      <DesktopFooter />
    </div>
  );
}
