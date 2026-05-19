"use client";

import { usePathname } from "next/navigation";
import DesktopHeader from "./header";
import MobileHeader from "./mobileheader";
import MobileBottomNav from "./mobilemanu";
import Footer from "./footer";
import { CourseEnglishSettingsProvider } from "@/lib/courseenglishSettings";
import { CourseEnglishInteractionsProvider, useCourseEnglishInteractions } from "@/lib/courseenglishInteractions";

const HIDE_CHROME_PREFIXES = [
  "/admin",
  "/agent",
  "/student",
  "/login",
  "/signup",
  "/forget",
  "/verify",
];

export default function SiteShell({ children, initialLanguage }) {
  const pathname = usePathname();
  const hideChrome = HIDE_CHROME_PREFIXES.some((prefix) => pathname.startsWith(prefix));
  const hideMobileChrome =
    pathname.startsWith("/language-institutes/") ||
    pathname.startsWith("/booking");

  if (hideChrome) {
    return (
      <CourseEnglishSettingsProvider initialLanguage={initialLanguage}>
        {children}
      </CourseEnglishSettingsProvider>
    );
  }

  return (
    <CourseEnglishSettingsProvider initialLanguage={initialLanguage}>
      <CourseEnglishInteractionsProvider>
        <ShellContent hideMobileChrome={hideMobileChrome}>{children}</ShellContent>
      </CourseEnglishInteractionsProvider>
    </CourseEnglishSettingsProvider>
  );
}

function ShellContent({ children, hideMobileChrome }) {
  const { wishlistCount, compareCount } = useCourseEnglishInteractions();

  return (
    <div className="min-h-screen bg-white text-slate-900">
      <DesktopHeader />
      {!hideMobileChrome && <MobileHeader />}
      {children}
      {!hideMobileChrome && <MobileBottomNav wishlistCount={wishlistCount} compareCount={compareCount} />}
      {hideMobileChrome ? <div className="hidden lg:block"><Footer /></div> : <Footer />}
    </div>
  );
}
