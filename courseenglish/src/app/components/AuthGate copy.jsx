"use client";

import { useEffect, useMemo, useState } from "react";
import { useRouter } from "next/navigation";

const ROLE_TO_DASHBOARD = {
  admin: "/admin/dashboard",
  lg_student: "/student/dashboard",
  student: "/student/dashboard",
  lg_agent: "/agent/dashboard",
  agent: "/agent/dashboard",
  uni_agent: "/agent/dashboard",
};

function getDashboardPath(role) {
  return ROLE_TO_DASHBOARD[role] || "/";
}

export default function AuthGate({ allowedRoles = [], children }) {
  const router = useRouter();
  const [checked, setChecked] = useState(false);
  const allowed = useMemo(() => new Set(allowedRoles), [allowedRoles]);

  useEffect(() => {
    try {
      const token = localStorage.getItem("auth_token");
      const rawUser = localStorage.getItem("auth_user");
      const user = rawUser ? JSON.parse(rawUser) : null;
      const role = user?.role;

      if (!token || !role) {
        router.replace("/login");
        return;
      }

      if (allowed.size && !allowed.has(role)) {
        router.replace(getDashboardPath(role));
        return;
      }

      setChecked(true);
    } catch {
      router.replace("/login");
    }
  }, [allowed, router]);

  if (!checked) return null;
  return children;
}
