"use client";

import {
  faArrowLeft,
  faArrowRight,
  faArrowRightArrowLeft,
  faBars,
  faBookmark,
  faChevronLeft,
  faChevronRight,
  faGrip,
  faHandHoldingDollar,
  faRightFromBracket,
  faUser,
  faUsers,
  faXmark,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import Image from "next/image";
import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useState } from "react";

import { useLocale } from "@/components/providers/locale-provider";
import { buildAuthUrl, clearAuthSession } from "@/lib/auth";

const MENU = [
  { href: "/agent/dashboard", icon: faGrip, key: "dashboard" },
  { href: "/agent/profile", icon: faUser, key: "profile" },
  { href: "/agent/students", icon: faUsers, key: "students" },
  { href: "/agent/bookings", icon: faBookmark, key: "bookings" },
  { href: "/compare", icon: faArrowRightArrowLeft, key: "compare" },
  { href: "/agent/referrals", icon: faHandHoldingDollar, key: "referrals" },
];

export default function AgentShell({ children }) {
  const pathname = usePathname();
  const router = useRouter();
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const loc = (key, fallback = "") => t(`pages.agent.nav.${key}`, fallback);

  const isActive = (href) => {
    if (href === "/agent/dashboard") return pathname === "/agent/dashboard";
    return pathname?.startsWith(href);
  };

  const currentPage = MENU.find((item) => isActive(item.href)) || MENU[0];

  const logout = async () => {
    try {
      const token = localStorage.getItem("auth_token");
      const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
      if (token) {
        await fetch(buildAuthUrl("/auth/logout"), {
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
      clearAuthSession();
      router.push("/");
    }
  };

  const pageTitle = loc(currentPage.key, currentPage.key);

  return (
    <div className="flex min-h-screen flex-col bg-[#F2F4F7]">
      <header className="border-b border-slate-200 bg-white" dir={direction}>
        <div className="mx-auto flex max-w-[1700px] items-center justify-between px-4 py-3 md:px-10">
          <div className="order-1 flex items-center gap-3">
            <div className="flex items-center gap-3 md:hidden">
              <button
                type="button"
                onClick={() => router.push("/")}
                className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-800 shadow-sm"
              >
                <FontAwesomeIcon icon={isRtl ? faArrowRight : faArrowLeft} />
              </button>
              <h1 className="text-xl font-medium text-[#102233]">{pageTitle}</h1>
            </div>

            <button
              type="button"
              onClick={() => router.push("/")}
              className="hidden items-center gap-2 rounded-2xl bg-[#E8F1F8] px-4 py-2.5 text-sm font-medium text-[#102233] md:inline-flex"
            >
              <FontAwesomeIcon icon={isRtl ? faChevronRight : faChevronLeft} className="text-sm" />
              {loc("back_to_site", "Back to site")}
            </button>
          </div>

          <div className="order-2 flex items-center gap-4">
            <Link href="/" className="flex items-center">
              <Image
                src="/assets/logo/logo.png"
                alt="Course English"
                width={160}
                height={56}
                className="h-8 w-auto object-contain md:h-14"
                unoptimized
              />
            </Link>
            <button
              type="button"
              onClick={() => setMobileMenuOpen(true)}
              className="grid h-10 w-10 place-items-center rounded-lg bg-slate-50 text-slate-700 lg:hidden"
            >
              <FontAwesomeIcon icon={faBars} className="text-xl" />
            </button>
          </div>
        </div>
      </header>

      <main
        className="mx-auto grid w-full max-w-[1700px] flex-1 grid-cols-1 gap-6 px-4 py-6 md:px-10 lg:grid-cols-[290px_minmax(0,1fr)] lg:gap-10"
        dir={direction}
      >
        <div className="hidden lg:block">
          <AgentSidebar isRtl={isRtl} isActive={isActive} logout={logout} loc={loc} onNavigate={null} />
        </div>
        <section>{children}</section>
      </main>

      {mobileMenuOpen && (
        <div className="fixed inset-0 z-50 flex lg:hidden" dir={direction}>
          <div
            className="fixed inset-0 bg-black/40 backdrop-blur-sm"
            onClick={() => setMobileMenuOpen(false)}
          />
          <div className="relative flex h-full w-72 max-w-[80vw] flex-col overflow-y-auto bg-[#F2F4F7] p-4 shadow-2xl">
            <button
              type="button"
              onClick={() => setMobileMenuOpen(false)}
              className={`absolute top-4 grid h-10 w-10 place-items-center rounded-full bg-white text-slate-500 shadow-sm ${isRtl ? "left-4" : "right-4"}`}
            >
              <FontAwesomeIcon icon={faXmark} className="text-xl" />
            </button>
            <div className="mt-12">
              <AgentSidebar
                isRtl={isRtl}
                isActive={isActive}
                logout={logout}
                loc={loc}
                onNavigate={() => setMobileMenuOpen(false)}
              />
            </div>
          </div>
        </div>
      )}

      <footer className="border-t border-slate-200 bg-white">
        <div
          className="mx-auto flex w-full max-w-[1700px] items-center justify-between px-6 py-3 text-sm text-slate-500 md:px-10"
          dir={direction}
        >
          <span>{loc("footer_rights", "All rights reserved © 2026 Course English")}</span>
          <span className="text-slate-400">{loc("footer_label", "Agent Portal")}</span>
        </div>
      </footer>
    </div>
  );
}

function AgentSidebar({ isRtl, isActive, logout, loc, onNavigate }) {
  return (
    <aside dir={isRtl ? "rtl" : "ltr"}>
      <div className="sticky top-6 rounded-3xl bg-white p-3.5 shadow-sm lg:border lg:border-slate-300 lg:shadow-none">
        <nav className="space-y-2">
          {MENU.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              onClick={onNavigate || undefined}
              className={`flex items-center gap-4 rounded-2xl px-4 py-3 text-md font-medium transition lg:py-2.5 ${
                isActive(item.href)
                  ? "bg-[#1277BE] text-white"
                  : "text-[#122333] hover:bg-slate-50"
              }`}
            >
              <FontAwesomeIcon
                icon={item.icon}
                className={`text-[18px] ${isActive(item.href) ? "text-white" : "text-[#1277BE]"}`}
              />
              <span>{loc(item.key, item.key)}</span>
            </Link>
          ))}

          <button
            type="button"
            onClick={() => {
              onNavigate?.();
              logout();
            }}
            className="flex w-full items-center gap-4 rounded-2xl px-4 py-3 text-md font-medium text-rose-600 hover:bg-rose-50 lg:py-2.5"
          >
            <FontAwesomeIcon icon={faRightFromBracket} className="text-[18px]" />
            <span>{loc("logout", "Logout")}</span>
          </button>
        </nav>
      </div>
    </aside>
  );
}
