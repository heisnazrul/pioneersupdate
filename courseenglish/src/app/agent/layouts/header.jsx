"use client";

import { useEffect, useMemo, useState } from "react";
import { useRouter } from "next/navigation";

function formatRole(role) {
  if (!role) return "Agent";
  return role.replace(/[_-]/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());
}

export default function Header() {
  const router = useRouter();
  const [user, setUser] = useState(null);
  const [loggingOut, setLoggingOut] = useState(false);

  useEffect(() => {
    try {
      const raw = localStorage.getItem("auth_user");
      setUser(raw ? JSON.parse(raw) : null);
    } catch {
      setUser(null);
    }
  }, []);

  const initials = useMemo(() => {
    if (!user?.name) return "AG";
    const parts = user.name.trim().split(/\s+/);
    const first = parts[0]?.[0] || "";
    const last = parts[parts.length - 1]?.[0] || "";
    return `${first}${last}`.toUpperCase();
  }, [user]);

  const handleLogout = async () => {
    if (loggingOut) return;
    setLoggingOut(true);
    try {
      const API_BASE = process.env.NEXT_PUBLIC_API_BASE_URL;
      const token = localStorage.getItem("auth_token");
      const tokenType = localStorage.getItem("auth_token_type") || "Bearer";

      if (API_BASE && token) {
        await fetch(`${API_BASE}auth/logout`, {
          method: "POST",
          headers: {
            Authorization: `${tokenType} ${token}`,
            "Content-Type": "application/json",
          },
        });
      }
    } catch {
      // ignore logout errors and still clear local auth
    } finally {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("auth_token_type");
      localStorage.removeItem("auth_user");
      router.push("/login");
    }
  };

  const displayName = user?.name || "Agent";
  const roleLabel = formatRole(user?.role);
  const detail = user?.email || roleLabel;

  return (
    <header className="flex items-center justify-between border-b border-slate-200 bg-white px-8 py-4 shadow-sm">
      <div className="flex items-center gap-3">
        <div className="rounded-lg bg-slate-900 px-3 py-2 text-sm font-normal text-white">
          {roleLabel}
        </div>
        <div className="flex items-center gap-2 text-xs font-normal uppercase tracking-[0.18em] text-slate-500">
          <span className="inline-flex h-2 w-2 rounded-full bg-emerald-500" aria-hidden />
          Online
        </div>
      </div>

      <div className="flex flex-1 items-center justify-end gap-3">
        <div className="relative hidden max-w-md flex-1 lg:block">
          <input
            type="search"
            placeholder="Search leads, tickets, or conversations"
            className="w-full rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-100"
          />
          <span className="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs font-normal text-slate-400">
            /
          </span>
        </div>
        <button
          type="button"
          onClick={handleLogout}
          disabled={loggingOut}
          className="hidden items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-normal text-rose-600 shadow-sm transition hover:border-rose-300 hover:bg-rose-100 disabled:cursor-not-allowed disabled:opacity-70 sm:inline-flex"
        >
          {loggingOut ? "Signing out..." : "Logout"}
        </button>
        <div className="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-3 py-2 shadow-sm">
          <div className="text-right">
            <p className="text-sm font-normal text-slate-800">{displayName}</p>
            <p className="text-xs text-slate-500">{detail}</p>
          </div>
          <div className="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-sm font-normal text-white">
            {initials}
          </div>
        </div>
      </div>
    </header>
  );
}
