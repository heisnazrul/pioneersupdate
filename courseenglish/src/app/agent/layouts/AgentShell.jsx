"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useApi, buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

const MENU = [
  { href: "/agent/dashboard", icon: "fa-grip", en: "Dashboard", ar: "لوحة التحكم" },
  { href: "/agent/bookings", icon: "fa-ticket", en: "Bookings", ar: "الحجوزات" },
  { href: "/agent/students", icon: "fa-users", en: "Students", ar: "الطلاب" },
  { href: "/agent/referrals", icon: "fa-hand-holding-dollar", en: "Referrals", ar: "الإحالات" },
  { href: "/agent/conversations", icon: "fa-comments", en: "Conversations", ar: "المحادثات" },
  { href: "/agent/knowledge", icon: "fa-book-open", en: "Knowledge Base", ar: "مركز المعرفة" },
];

export default function AgentShell({ children }) {
  const pathname = usePathname();
  const router = useRouter();
  const { data } = useApi("/courseenglish/home/branding");
  const { isArabic } = useCourseEnglishSettings();
  const logoSrc = data?.branding?.header?.logo?.main || "/logo.png";

  const isActive = (href) => {
    if (href === "/agent/dashboard") return pathname === "/agent/dashboard";
    return href !== "#" && pathname?.startsWith(href);
  };

  const logout = async () => {
    try {
      const token = localStorage.getItem("auth_token");
      const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
      if (token) {
        await fetch(buildApiUrl("/auth/logout"), {
          method: "POST",
          headers: {
            Accept: "application/json",
            Authorization: `${tokenType} ${token}`,
          },
        });
      }
    } catch {
      // ignore
    } finally {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("auth_token_type");
      localStorage.removeItem("auth_user");
      router.push("/login");
    }
  };

  return (
    <div className="flex min-h-screen flex-col bg-[#F2F4F7]">
      <header className="border-b border-slate-200 bg-white" dir={isArabic ? "rtl" : "ltr"}>
        <div className="mx-auto flex max-w-[1700px] items-center justify-between px-6 py-4 md:px-10">
          <Link href="/" className="flex items-center">
            <img src={logoSrc} alt="CourseEnglish" className="h-14 w-auto" loading="lazy" />
          </Link>
          <div className="flex items-center gap-3">
            <button
              type="button"
              onClick={() => router.push("/")}
              className="inline-flex items-center gap-2 rounded-2xl bg-[#E8F1F8] px-4 py-2.5 text-sm font-medium text-[#102233]"
            >
              <i className={`fa-solid fa-chevron-${isArabic ? "right" : "left"} text-sm`} />
              {isArabic ? "العودة الى الموقع" : "Back to site"}
            </button>
            <button
              type="button"
              onClick={logout}
              className="hidden md:inline-flex items-center gap-2 rounded-2xl bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-600"
            >
              <i className="fa-solid fa-right-from-bracket text-sm" />
              {isArabic ? "تسجيل الخروج" : "Logout"}
            </button>
          </div>
        </div>
      </header>

      <main
        className="mx-auto grid w-full max-w-[1700px] flex-1 grid-cols-1 gap-10 px-6 py-6 md:px-10 lg:grid-cols-[290px_minmax(0,1fr)]"
        dir={isArabic ? "rtl" : "ltr"}
      >
        <Sidebar isArabic={isArabic} isActive={isActive} logout={logout} />
        <section dir={isArabic ? "rtl" : "ltr"}>{children}</section>
      </main>

      <footer className="border-t border-slate-200 bg-white">
        <div
          className="mx-auto flex w-full max-w-[1700px] items-center justify-between px-6 py-3 text-sm text-slate-500 md:px-10"
          dir={isArabic ? "rtl" : "ltr"}
        >
          <span>{isArabic ? "الحقوق محفوظة 2026 كورس إنجليزي" : "2026 All rights reserved"}</span>
          <span className="text-slate-400">{isArabic ? "لوحة الوكلاء" : "CourseEnglish Agent"}</span>
        </div>
      </footer>
    </div>
  );
}

function Sidebar({ isArabic, isActive, logout }) {
  return (
    <aside dir={isArabic ? "rtl" : "ltr"}>
      <div className="sticky top-6 rounded-3xl border border-slate-300 bg-white p-3.5">
        <nav className="space-y-2">
          {MENU.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className={`flex items-center gap-4 rounded-2xl px-4 py-2.5 text-md font-medium transition ${
                isActive(item.href) ? "bg-[#1277BE] text-white" : "text-[#122333] hover:bg-slate-50"
              } ${item.href === "#" ? "pointer-events-none opacity-70" : ""}`}
            >
              <i className={`fa-solid ${item.icon} text-[18px] ${isActive(item.href) ? "text-white" : "text-[#1277BE]"}`} />
              <span>{isArabic ? item.ar : item.en}</span>
            </Link>
          ))}

          <button
            type="button"
            onClick={logout}
            className="flex w-full gap-4 items-center  rounded-2xl px-4 py-2.5 text-md font-medium text-rose-600 hover:bg-rose-50"
          >
            <i className="fa-solid fa-right-from-bracket text-[18px]" />
            <span>{isArabic ? "تسجيل الخروج" : "Logout"}</span>
          </button>
        </nav>
      </div>
    </aside>
  );
}
