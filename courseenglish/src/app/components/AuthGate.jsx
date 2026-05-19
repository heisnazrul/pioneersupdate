"use client";

import { useEffect, useMemo, useState } from "react";
import { useRouter } from "next/navigation";
import {
  clearCourseEnglishAuthSession,
  fetchCourseEnglishMe,
  getCourseEnglishDashboardPath,
  getStoredAuthToken,
  getStoredAuthUser,
  isCourseEnglishRole,
  saveCourseEnglishAuthSession,
} from "@/lib/courseenglishAuth";

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
        router.replace("/login");
        return;
      }

      const meJson = await fetchCourseEnglishMe(token);
      const liveRole = meJson?.user?.role || role;

      if (!isCourseEnglishRole(liveRole)) {
        clearCourseEnglishAuthSession();
        router.replace("/login");
        return;
      }

      saveCourseEnglishAuthSession(meJson);

      if (allowed.size && !allowed.has(liveRole)) {
        router.replace(getCourseEnglishDashboardPath(liveRole));
        return;
      }

      setChecked(true);
      } catch {
      clearCourseEnglishAuthSession();
      router.replace("/login");
      }
    };

    run();
  }, [allowed, router]);

  if (!checked) return null;
  return children;
}
