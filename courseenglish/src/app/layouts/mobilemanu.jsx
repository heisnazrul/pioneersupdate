// components/MobileBottomNav.js
"use client";

import Image from "next/image";
import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useState } from "react";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";
import {
  PhoneSignUpSheet,
  EmailSignUpSheet,
  EmailLoginSheet,
} from "@/app/components/AuthBottomSheets";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faUser,
  faHouse,
} from "@fortawesome/free-regular-svg-icons";
import { faArrowRightArrowLeft } from "@fortawesome/free-solid-svg-icons";
import { faSchool } from "@fortawesome/free-solid-svg-icons";
import { getCourseEnglishMessages } from "@/lib/courseenglishLocale";
import {
  clearCourseEnglishAuthSession,
  fetchCourseEnglishMe,
  getCourseEnglishDashboardPath,
  getStoredAuthToken,
  getStoredAuthUser,
  isCourseEnglishRole,
  saveCourseEnglishAuthSession,
} from "@/lib/courseenglishAuth";

export default function MobileBottomNav({ wishlistCount = 0, compareCount = 0 }) {
  const pathname = usePathname();
  const router = useRouter();
  const { language } = useCourseEnglishSettings();
  const [openPhone, setOpenPhone] = useState(false);
  const [openLogin, setOpenLogin] = useState(false);
  const [openEmail, setOpenEmail] = useState(false);

  const locale = getCourseEnglishMessages(language);
  const t = locale?.layouts?.navbar?.bottom_nav ?? {
    account: language === "ar" ? "حسابي" : "My Account",
    compare: language === "ar" ? "المقارنة" : "Compare",
    wishlist: language === "ar" ? "المفضلة" : "Wishlist",
    institutes: language === "ar" ? "المعاهد" : "Institutes",
    home: language === "ar" ? "الرئيسية" : "Home",
  };

  const items = [
    {
      key: "account",
      label: t.account,
      href: "/student/dashboard",
      icon: faUser,
    },
    {
      key: "compare",
      label: t.compare,
      href: "/compare",
      icon: faArrowRightArrowLeft,
      compareBadge: true,
    },
    {
      key: "wishlist",
      label: t.wishlist,
      href: "/wishlist",
      iconType: "heart",
      showBadge: true,
    },
    {
      key: "institutes",
      label: t.institutes,
      href: "/language-institutes",
      icon: faSchool,
    },
    {
      key: "home",
      label: t.home,
      href: "/",
      icon: faHouse,
    },
  ];

  const isActive = (href) => {
    if (href === "/") return pathname === "/";
    return pathname.startsWith(href);
  };

  const handleAccountClick = async () => {
    try {
      const token = getStoredAuthToken();
      const user = getStoredAuthUser();

      if (!token || !isCourseEnglishRole(user?.role)) {
        setOpenLogin(true);
        return;
      }

      const meJson = await fetchCourseEnglishMe(token);
      const role = meJson?.user?.role || user.role;
      if (!isCourseEnglishRole(role)) {
        clearCourseEnglishAuthSession();
        setOpenLogin(true);
        return;
      }
      saveCourseEnglishAuthSession(meJson);
      router.push(getCourseEnglishDashboardPath(role));
    } catch {
      clearCourseEnglishAuthSession();
      setOpenLogin(true);
    }
  };

  return (
    <>
      <nav
        className="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white md:hidden pb-[calc(env(safe-area-inset-bottom,0px)+8px)] shadow-[0_-6px_18px_rgba(0,0,0,0.08)]"
        aria-label="Bottom navigation"
      >
        <div className="mx-auto flex max-w-md items-stretch justify-between px-2 py-2 text-[11px] text-slate-500">
          {items.map((item) => {
            const active = isActive(item.href);
            const isAccount = item.key === "account";

            const content = (
              <>
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
                      alt="Wishlist"
                      width={18}
                      height={18}
                      className="h-[18px] w-[18px]"
                    />
                  ) : (
                    <FontAwesomeIcon icon={item.icon} />
                  )}

                  {item.showBadge && wishlistCount > 0 && (
                    <span className="absolute -top-1 -right-1 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-normal text-white">
                      {wishlistCount}
                    </span>
                  )}
                  {item.compareBadge && compareCount > 0 && (
                    <span className="absolute -top-1 -right-1 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full bg-blue-500 px-1 text-[9px] font-normal text-white">
                      {compareCount}
                    </span>
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
              </>
            );

            if (isAccount) {
              return (
                <button
                  key={item.href}
                  type="button"
                  onClick={(e) => {
                    e.preventDefault();
                    handleAccountClick();
                  }}
                  className="flex flex-1 flex-col items-center justify-center gap-0.5 px-2 py-2"
                >
                  {content}
                </button>
              );
            }

            return (
              <Link
                key={item.href}
                href={item.href}
                className="flex flex-1 flex-col items-center justify-center gap-0.5 px-2 py-2"
              >
                {content}
              </Link>
            );
          })}
        </div>
      </nav>

      <PhoneSignUpSheet
        isOpen={openPhone}
        onClose={() => setOpenPhone(false)}
        onOpenLogin={() => {
          setOpenPhone(false);
          setOpenLogin(true);
        }}
        onOpenEmailSignUp={() => {
          setOpenPhone(false);
          setOpenEmail(true);
        }}
      />
      <EmailSignUpSheet
        isOpen={openEmail}
        onClose={() => setOpenEmail(false)}
        onUsePhone={() => {
          setOpenEmail(false);
          setOpenPhone(true);
        }}
      />
      <EmailLoginSheet
        isOpen={openLogin}
        onClose={() => setOpenLogin(false)}
        onUsePhone={() => {
          setOpenLogin(false);
          setOpenPhone(true);
        }}
      />
    </>
  );
}
