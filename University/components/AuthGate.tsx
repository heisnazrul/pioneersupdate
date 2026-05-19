"use client";

import { useEffect, useMemo } from "react";
import { useRouter } from "next/navigation";
import { useAuth } from "@/context/AuthContext";
import { isUniversityRole } from "@/services/auth";

const ROLE_TO_DASHBOARD: Record<string, string> = {
  uni_student: "/student/dashboard",
  uni_agent: "/agent/dashboard",
};

function getDashboardPath(role?: string) {
  if (!role) return "/";
  return ROLE_TO_DASHBOARD[role] || "/";
}

export default function AuthGate({
  allowedRoles = [],
  children,
}: {
  allowedRoles?: string[];
  children: React.ReactNode;
}) {
  const router = useRouter();
  const { user, isAuthenticated, isLoading } = useAuth();
  const allowed = useMemo(() => new Set(allowedRoles), [allowedRoles]);
  const isAuthorized = !!user && isUniversityRole(user.role) && (!allowed.size || allowed.has(user.role || ""));

  useEffect(() => {
    if (isLoading) return;

    if (!isAuthenticated || !user || !isUniversityRole(user.role)) {
      router.replace("/login");
      return;
    }

    if (allowed.size && !allowed.has(user.role || "")) {
      router.replace(getDashboardPath(user.role));
    }
  }, [user, isAuthenticated, isLoading, allowed, router]);

  if (isLoading || !isAuthorized) {
    return null;
  }

  return children;
}
