"use client";

import { useState } from "react";
import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useApi, buildApiUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

const MENU = [
  { href: "/student/dashboard", icon: "fa-grip", en: "Dashboard", ar: "لوحة التحكم" },
  { href: "/student/profile", icon: "fa-user", en: "Profile", ar: "الملف الشخصي" },
  { href: "/student/bookings", icon: "fa-bookmark", en: "My Bookings", ar: "حجوزاتي" },
  { href: "/student/wishlist", icon: "fa-heart", en: "Wishlist", ar: "المفضلة" },
  { href: "/student/compare", icon: "fa-arrow-right-arrow-left", en: "Compare", ar: "المقارنة" },
  { href: "/referrals", icon: "fa-hand-holding-dollar", en: "Referrals", ar: "برنامج الاحالة" },
];

export default function StudentShell({ children }) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const pathname = usePathname();
  const router = useRouter();
  const { data } = useApi("/courseenglish/home/branding");
  const { isArabic } = useCourseEnglishSettings();
  const logoSrc = data?.branding?.header?.logo?.main || "/logo.png";

  const isActive = (href) => {
    if (href === "/student/dashboard") return pathname === "/student/dashboard";
    return pathname?.startsWith(href);
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
      router.push("/");
    }
  };

  const currentPage = MENU.find((m) => isActive(m.href)) || MENU[0];
  const pageTitle = isArabic ? currentPage.ar : currentPage.en;

  return (
    <div className="flex min-h-screen flex-col bg-[#F2F4F7]">
      <header className="border-b border-slate-200 bg-white" dir={isArabic ? "rtl" : "ltr"}>
        <div className="mx-auto flex max-w-[1700px] items-center justify-between px-4 py-3 md:px-10">
          
          <div className="order-1 flex items-center gap-3">
            {/* Mobile Back Button + Title */}
            <div className="flex md:hidden items-center gap-3">
              <button
                onClick={() => router.push("/")}
                className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white shadow-sm border border-slate-200 text-slate-800"
              >
                 <i className={`fa-solid fa-arrow-${isArabic ? "right" : "left"} text-md`} />
              </button>
              <h1 className="text-xl font-medium text-[#102233]">{pageTitle}</h1>
            </div>

            {/* PC Back Button */}
            <button
              type="button"
              onClick={() => router.push("/")}
              className="hidden md:inline-flex items-center gap-2 rounded-2xl bg-[#E8F1F8] px-4 py-2.5 text-sm font-medium text-[#102233]"
            >
              <i className={`fa-solid fa-chevron-${isArabic ? "right" : "left"} text-sm`} />
              {isArabic ? "العودة الى الموقع" : "Back to site"}
            </button>
          </div>

          <div className="order-2 flex items-center gap-4">
            <Link href="/" className="flex items-center">
              <img src={logoSrc} alt="CourseEnglish" className="h-[2rem] md:h-14 w-auto object-contain" loading="lazy" />
            </Link>
            {/* Hamburger on mobile */}
            <button onClick={() => setMobileMenuOpen(true)} className="lg:hidden grid h-10 w-10 place-items-center rounded-lg bg-slate-50 text-slate-700">
               <i className="fa-solid fa-bars text-xl" />
            </button>
          </div>
          
        </div>
      </header>

      <main
        className="mx-auto grid w-full max-w-[1700px] flex-1 grid-cols-1 gap-6 lg:gap-10 px-4 py-6 md:px-10 lg:grid-cols-[290px_minmax(0,1fr)]"
        dir={isArabic ? "rtl" : "ltr"}
      >
        <div className="hidden lg:block">
          <Sidebar isArabic={isArabic} isActive={isActive} logout={logout} />
        </div>
        <section dir={isArabic ? "rtl" : "ltr"}>{children}</section>
      </main>

      {/* Mobile Drawer Overlay */}
      {mobileMenuOpen && (
        <div className="fixed inset-0 z-50 flex" dir={isArabic ? "rtl" : "ltr"}>
          {/* Backdrop */}
          <div className="fixed inset-0 bg-black/40 backdrop-blur-sm" onClick={() => setMobileMenuOpen(false)} />
          {/* Sidebar Drawer */}
          <div className="relative w-72 max-w-[80vw] bg-[#F2F4F7] p-4 flex flex-col h-full overflow-y-auto shadow-2xl">
            <button onClick={() => setMobileMenuOpen(false)} className="absolute top-4 left-4 grid h-10 w-10 place-items-center rounded-full bg-white text-slate-500 shadow-sm" style={{ [isArabic ? "left" : "right"]: "1rem", [isArabic ? "right" : "left"]: "auto" }}>
              <i className="fa-solid fa-xmark text-xl" />
            </button>
            <div className="mt-12">
              <Sidebar isArabic={isArabic} isActive={isActive} logout={logout} onNavigate={() => setMobileMenuOpen(false)} />
            </div>
          </div>
        </div>
      )}

      <footer className="border-t border-slate-200 bg-white">
        <div
          className="mx-auto flex w-full max-w-[1700px] items-center justify-between px-6 py-3 text-sm text-slate-500 md:px-10"
          dir={isArabic ? "rtl" : "ltr"}
        >
          <span>{isArabic ? "الحقوق محفوظة 2026 كورس انجليزي" : "2026 All rights reserved "}</span>
          <span className="text-slate-400">{isArabic ? "CourseEnglish" : "CourseEnglish"}</span>
        </div>
      </footer>
    </div>
  );
}

function Sidebar({ isArabic, isActive, logout, onNavigate }) {
  return (
    <aside dir={isArabic ? "rtl" : "ltr"}>
      <div className="sticky top-6 rounded-3xl lg:border lg:border-slate-300 bg-white p-3.5 shadow-sm lg:shadow-none">
        <nav className="space-y-2">
          {MENU.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              onClick={onNavigate}
              className={`flex items-center gap-4 rounded-2xl px-4 py-3 lg:py-2.5 text-md font-medium transition ${
                isActive(item.href) ? "bg-[#1277BE] text-white" : "text-[#122333] hover:bg-slate-50"
              }`}
            >
              <i className={`fa-solid ${item.icon} text-[18px] ${isActive(item.href) ? "text-white" : "text-[#1277BE]"}`} />
              <span>{isArabic ? item.ar : item.en}</span>
            </Link>
          ))}

          <button
            type="button"
            onClick={() => {
               if(onNavigate) onNavigate();
               logout();
            }}
            className="flex w-full gap-4 items-center  rounded-2xl px-4 py-3 lg:py-2.5 text-md font-medium text-rose-600 hover:bg-rose-50"
          >
            <i className="fa-solid fa-right-from-bracket text-[18px]" />
            <span>{isArabic ? "تسجيل الخروج" : "Logout"}</span>
          </button>
        </nav>
      </div>
    </aside>
  );
}
