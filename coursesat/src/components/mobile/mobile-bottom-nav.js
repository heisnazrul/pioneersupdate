"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUser, faHouse } from "@fortawesome/free-regular-svg-icons";
import { faArrowRightArrowLeft, faSchool } from "@fortawesome/free-solid-svg-icons";
import Image from "next/image";

import { useLocale } from "@/components/providers/locale-provider";
import { getStoredAuthToken, getStoredAuthUser } from "@/lib/auth";

export default function MobileBottomNav() {
  const pathname = usePathname();
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const isAuthenticated = Boolean(getStoredAuthToken());
  const userRole = getStoredAuthUser()?.role || "";
  const wishlistHref = isAuthenticated
    ? "/student/wishlist"
    : `/login?redirect=${encodeURIComponent("/student/wishlist")}`;

  const baseItems = [
    {
      key: "account",
      label: t("layouts.navbar.bottom_nav.account", "My Account"),
      href: isAuthenticated ? (userRole === "lg_agent" ? "/agent/dashboard" : "/student/dashboard") : "/login",
      icon: faUser,
    },
    {
      key: "compare",
      label: t("layouts.navbar.bottom_nav.compare", "Compare"),
      href: "/compare",
      icon: faArrowRightArrowLeft,
    },
    {
      key: "wishlist",
      label: t("layouts.navbar.bottom_nav.wishlist", "Wishlist"),
      href: wishlistHref,
      iconType: "heart",
    },
    {
      key: "institutes",
      label: t("layouts.navbar.bottom_nav.institutes", "Institutes"),
      href: "/language-institutes",
      icon: faSchool,
    },
    {
      key: "home",
      label: t("layouts.navbar.bottom_nav.home", "Home"),
      href: "/",
      icon: faHouse,
    },
  ];
  const items = isRtl ? [...baseItems].reverse() : baseItems;

  const isActive = (href) => {
    if (href === "/") return pathname === "/";
    return pathname.startsWith(href);
  };

  return (
    <nav
      className="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white pb-[calc(env(safe-area-inset-bottom,0px)+8px)] shadow-[0_-6px_18px_rgba(0,0,0,0.08)]"
      aria-label="Bottom navigation"
      dir={direction}
    >
      <div className="mx-auto flex max-w-md items-stretch justify-between px-2 py-2 text-[11px] text-slate-500">
        {items.map((item) => {
          const active = isActive(item.href);

          return (
            <Link
              key={item.key}
              href={item.href}
              className="flex flex-1 flex-col items-center justify-center gap-0.5 px-2 py-2"
            >
              <div
                className={
                  "relative flex h-8 w-8 items-center justify-center rounded-full text-[16px] " +
                  (active
                    ? "bg-[#135FAE]/10 text-[#135FAE]"
                    : "text-slate-500")
                }
              >
                {item.iconType === "heart" ? (
                  <Image
                    src="/assets/icons/heart-regular-black.svg"
                    alt={item.label}
                    width={18}
                    height={18}
                    className="h-[18px] w-[18px]"
                  />
                ) : (
                  <FontAwesomeIcon icon={item.icon} />
                )}
              </div>
              <span
                className={
                  "mt-0.5 font-normal " +
                  (active ? "text-[#135FAE]" : "text-slate-500")
                }
              >
                {item.label}
              </span>
            </Link>
          );
        })}
      </div>
    </nav>
  );
}
