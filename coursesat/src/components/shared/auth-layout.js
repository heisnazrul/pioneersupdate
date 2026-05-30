"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import Image from "next/image";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons";

import { useLocale } from "@/components/providers/locale-provider";
import {
  clearAuthSession,
  fetchAuthMe,
  getDashboardPath,
  getStoredAuthToken,
  getStoredAuthUser,
  isCourseEnglishRole,
  saveAuthSession,
} from "@/lib/auth";

export default function AuthLayout({ title, subtitle, children }) {
  const { direction, t } = useLocale();
  const isRtl = direction === "rtl";
  const [authed, setAuthed] = useState(false);
  const [dashboardPath, setDashboardPath] = useState("/");

  useEffect(() => {
    const run = async () => {
      try {
        const token = getStoredAuthToken();
        const user = getStoredAuthUser();
        if (!token || !isCourseEnglishRole(user?.role)) {
          setAuthed(false);
          return;
        }

        const meJson = await fetchAuthMe(token);
        const role = meJson?.user?.role || user.role;
        if (!isCourseEnglishRole(role)) {
          clearAuthSession();
          setAuthed(false);
          return;
        }
        saveAuthSession(meJson);
        setAuthed(true);
        setDashboardPath(getDashboardPath(role));
      } catch {
        clearAuthSession();
        setAuthed(false);
      }
    };

    run();
  }, []);

  return (
    <div
      className="relative flex min-h-screen w-full items-center justify-center overflow-hidden bg-[#EBF5FF] p-4 lg:p-8"
      dir={direction}
    >
      <div className="pointer-events-none absolute inset-0 z-0">
        <div className="absolute left-[-20%] top-[-20%] h-[800px] w-[800px] rounded-full bg-blue-200/40 blur-[120px]" />
        <div className="absolute bottom-[-10%] right-[-10%] h-[600px] w-[600px] rounded-full bg-blue-300/30 blur-[100px]" />
        <div className="absolute left-[20%] top-[40%] h-[400px] w-[400px] rounded-full bg-white/40 blur-[80px]" />
      </div>

      <div className="absolute left-0 top-0 z-20 flex w-full items-center justify-between p-6 lg:p-10">
        <div className="relative h-12 w-40">
          <Image
            src="/assets/logo/logo.png"
            alt="Course English"
            fill
            className={`object-contain ${isRtl ? "object-right" : "object-left"}`}
            unoptimized
          />
        </div>

        <Link href="/" className="group flex items-center gap-2 transition hover:opacity-80">
          <div className="flex h-8 w-8 items-center justify-center text-slate-700 transition group-hover:scale-105">
            <FontAwesomeIcon
              icon={faArrowLeft}
              className={`text-base ${isRtl ? "rotate-180" : ""}`}
            />
          </div>
          <span className="hidden text-sm font-medium text-slate-600 transition group-hover:text-slate-900 md:inline-block">
            {t("pages.login.back_to_home", "Back to Home")}
          </span>
        </Link>
      </div>

      <div className="relative z-10 mt-20 grid w-full max-w-6xl items-center gap-12 lg:mt-0 lg:grid-cols-2">
        <div className="order-1 mx-auto w-full max-w-md">
          <div className="relative rounded-[2rem] border border-blue-50 bg-white p-8 shadow-xl shadow-blue-900/5 md:p-12">
            <div className="mb-8 text-center">
              <h1 className="mb-3 text-2xl font-semibold text-slate-900">{title}</h1>
              <p className="text-sm font-normal leading-relaxed text-slate-500">{subtitle}</p>
            </div>

            {authed ? (
              <div className="space-y-4">
                <div className="rounded-2xl border border-blue-100 bg-blue-50 px-6 py-4 text-center">
                  <p className="mb-2 font-medium text-[#135FAE]">
                    {t("pages.login.already_logged_in", "You are already logged in")}
                  </p>
                  <p className="mb-4 text-xs text-slate-500">
                    {t("pages.login.dashboard_desc", "You can go directly to your dashboard.")}
                  </p>
                  <Link
                    href={dashboardPath}
                    className="inline-flex w-full transform items-center justify-center rounded-xl bg-[#135FAE] py-3 text-sm font-medium text-white shadow-lg transition hover:scale-[1.02] active:scale-95"
                  >
                    {t("pages.login.go_to_dashboard", "Go to Dashboard")}
                  </Link>
                </div>
              </div>
            ) : (
              children
            )}
          </div>
        </div>

        <div className={`order-2 hidden lg:block ${isRtl ? "text-right" : "text-left"}`}>
          <h2 className="mb-8 text-5xl font-semibold leading-[1.3] text-slate-900 drop-shadow-sm">
            {t(
              "pages.login.side_title",
              "We help you choose the best language institute for you, with confidence and clarity."
            )}
          </h2>
          <p className="max-w-lg text-xl font-normal leading-relaxed text-slate-600">
            {t(
              "pages.login.side_text",
              "A specialized platform for displaying and comparing accredited language institutes around the world, helping you make the most appropriate decision before booking."
            )}
          </p>
        </div>
      </div>
    </div>
  );
}
