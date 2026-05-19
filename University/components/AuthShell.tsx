"use client";

import { useEffect, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { authService, clearAuthSession, getStoredToken, getStoredUser, isUniversityRole, storeAuthSession } from "@/services/auth";

const ROLE_TO_DASHBOARD: Record<string, string> = {
  uni_student: "/student/dashboard",
  uni_agent: "/agent/dashboard",
};

function getDashboardPath(role?: string) {
  if (!role) return "/";
  return ROLE_TO_DASHBOARD[role] || "/";
}

export default function AuthShell({
  title,
  subtitle,
  children,
  sideTitle = "Study abroad with confidence",
  sideText = "Access real offers, expert guidance, and student support in one place.",
}: {
  title: string;
  subtitle?: string;
  children: React.ReactNode;
  sideTitle?: string;
  sideText?: string;
}) {
  const [authed, setAuthed] = useState(false);
  const [dashboardPath, setDashboardPath] = useState("/");

  useEffect(() => {
    const run = async () => {
      const token = getStoredToken();
      const user = getStoredUser();

      if (!token || !isUniversityRole(user?.role)) {
        setAuthed(false);
        return;
      }

      try {
        const freshUser = await authService.getUser(token);

        if (!isUniversityRole(freshUser.role)) {
          clearAuthSession();
          setAuthed(false);
          return;
        }

        storeAuthSession({ token, tokenType: "Bearer", user: freshUser });
        setAuthed(true);
        setDashboardPath(getDashboardPath(freshUser.role));
      } catch {
        clearAuthSession();
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
            <Image
              src="/logo.webp"
              alt="Pioneers Admissions"
              width={150}
              height={42}
              className="h-30 w-auto"
              priority
            />
          </Link>
          <h1 className="mt-5 hidden text-3xl font-extrabold text-slate-900 sm:text-4xl md:block">
            {sideTitle}
          </h1>
          <p className="mt-4 hidden text-base leading-7 text-slate-600 md:block">
            {sideText}
          </p>
          <div className="mt-6 hidden gap-3 sm:grid sm:grid-cols-2">
            {[
              { label: "Verified universities", value: "120+ partners" },
              { label: "Expert support", value: "24/7 guidance" },
              { label: "Best offers", value: "Exclusive deals" },
              { label: "Secure signup", value: "Your data protected" },
            ].map((item) => (
              <div
                key={item.label}
                className="rounded-2xl border border-[#E4EDF8] bg-white px-4 py-3 shadow-sm"
              >
                <div className="text-xs font-semibold text-slate-500">
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
                className="inline-flex w-full items-center justify-center rounded-full bg-[#1F63AE] py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#175093]"
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
