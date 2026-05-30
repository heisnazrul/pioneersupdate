"use client";

import { useEffect, useMemo, useState } from "react";
import { useRouter } from "next/navigation";

import {
  clearAuthSession,
  fetchAuthMe,
  getDashboardPath,
  getStoredAuthToken,
  getStoredAuthUser,
  isCourseEnglishRole,
  saveAuthSession,
} from "@/lib/auth";

export default function AuthGate({ allowedRoles = [], children }) {
  const router = useRouter();
  const [checked, setChecked] = useState(false);
  const allowed = useMemo(() => new Set(allowedRoles), [allowedRoles]);

  useEffect(() => {
    const run = async () => {
      try {
        const token = getStoredAuthToken();
        const user = getStoredAuthUser();
        const role = user?.role;

        if (!token || !isCourseEnglishRole(role)) {
          const redirect = typeof window !== "undefined"
            ? `${window.location.pathname}${window.location.search}`
            : "/login";
          router.replace(`/login?redirect=${encodeURIComponent(redirect)}`);
          return;
        }

        const meJson = await fetchAuthMe(token);
        const liveRole = meJson?.user?.role || role;

        if (!isCourseEnglishRole(liveRole)) {
          clearAuthSession();
          router.replace("/login");
          return;
        }

        saveAuthSession(meJson);

        if (allowed.size && !allowed.has(liveRole)) {
          router.replace(getDashboardPath(liveRole));
          return;
        }

        setChecked(true);
      } catch {
        clearAuthSession();
        router.replace("/login");
      }
    };

    run();
  }, [allowed, router]);

  if (!checked) return null;
  return children;
}
