"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import {
  clearCourseEnglishAuthSession,
  fetchCourseEnglishMe,
  getCourseEnglishDashboardPath,
  getStoredAuthToken,
  getStoredAuthUser,
  isCourseEnglishRole,
  saveCourseEnglishAuthSession,
} from "@/lib/courseenglishAuth";

export default function AuthShell({
  title,
  subtitle,
  children,
  sideTitle = "Study English with confidence",
  sideText = "Access real offers, expert guidance, and student support in one place.",
}) {
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

        const meJson = await fetchCourseEnglishMe(token);
        const role = meJson?.user?.role || user.role;

        if (!isCourseEnglishRole(role)) {
          clearCourseEnglishAuthSession();
          setAuthed(false);
          return;
        }

        saveCourseEnglishAuthSession(meJson);
        setAuthed(true);
        setDashboardPath(getCourseEnglishDashboardPath(role));
      } catch {
        clearCourseEnglishAuthSession();
        setAuthed(false);
      }
    };

    run();
  }, []);

  return (
    <div className="relative min-h-screen overflow-hidden bg-[#F5F8FF]">
      <div className="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[#D9E8FF] blur-3xl" />
      <div className="pointer-events-none absolute -bottom-28 -left-24 h-80 w-80 rounded-full bg-[#E7F1FF] blur-3xl" />

      <div className="relative mx-auto flex min-h-screen w-full max-w-6xl flex-col items-center justify-center gap-10 px-4 py-12 lg:flex-row lg:gap-16">
        <div className="w-full max-w-md text-center">
          <Link href="/" className="inline-flex items-center gap-3">
            <img
              src="/logo.png"
              alt="Course"
              width={150}
              height={42}
              className="h-30 w-auto"
            />
          </Link>
          <h1 className="hidden md:block mt-5 text-3xl font-extrabold text-slate-900 sm:text-4xl">
            {sideTitle}
          </h1>
          <p className="hidden md:block mt-4 text-base leading-7 text-slate-600">
            {sideText}
          </p>
          <div className="mt-6 hidden md:grid gap-3 sm:grid-cols-2">
            {[
              { label: "Verified institutes", value: "120+ partners" },
              { label: "Expert support", value: "24/7 guidance" },
              { label: "Best offers", value: "Exclusive deals" },
              { label: "Secure signup", value: "Your data protected" },
            ].map((item) => (
              <div
                key={item.label}
                className="rounded-2xl border border-[#E4EDF8] bg-white px-4 py-3  shadow-sm"
              >
                <div className="text-xs font-normal text-slate-500">
                  {item.label}
                </div>
                <div className="text-sm font-extrabold text-slate-900">
                  {item.value}
                </div>
              </div>
            ))}
          </div>
        </div>

        <div className="w-full max-w-md rounded-3xl border border-[#E4EDF8] bg-white p-8 shadow-xl">
          <h2 className="text-2xl font-extrabold text-slate-900">{title}</h2>
          {subtitle ? (
            <p className="mt-2 text-sm text-slate-500">{subtitle}</p>
          ) : null}
          {authed ? (
            <div className="mt-6 space-y-4">
              <div className="rounded-2xl border border-[#D5E6FB] bg-[#F4F9FF] px-4 py-3 text-sm text-slate-600">
                You&apos;re already signed in. Continue to your dashboard.
              </div>
              <Link
                href={dashboardPath}
                className="inline-flex w-full items-center justify-center rounded-full bg-[#1F63AE] py-3 text-sm font-normal text-white shadow-md transition hover:bg-[#175093]"
              >
                Go to dashboard
              </Link>
            </div>
          ) : (
            children
          )}
        </div>
      </div>
    </div>
  );
}
