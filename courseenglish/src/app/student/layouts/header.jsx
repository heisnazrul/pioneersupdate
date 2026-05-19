"use client";

import { useEffect, useMemo, useState, useRef } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBars } from "@fortawesome/free-solid-svg-icons";
import { useApi } from "@/lib/courseenglishApi";

function formatRole(role) {
  if (!role) return "Student";
  return role.replace(/[_-]/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());
}

export default function Header({ onToggleSidebar }) {
  const router = useRouter();
  const [user, setUser] = useState(null);
  const [loggingOut, setLoggingOut] = useState(false);
  const [isDropdownOpen, setDropdownOpen] = useState(false);
  const dropdownRef = useRef(null);

  // Fetch branding for logo
  const { data: brandingData } = useApi("/courseenglish/home/branding");
  const logoSrc = brandingData?.branding?.header?.logo?.main || "/logo.png";

  useEffect(() => {
    try {
      const raw = localStorage.getItem("auth_user");
      setUser(raw ? JSON.parse(raw) : null);
    } catch {
      setUser(null);
    }
  }, []);

  // Close dropdown on outside click
  useEffect(() => {
    function handleClickOutside(event) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setDropdownOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const initials = useMemo(() => {
    if (!user?.name) return "ST";
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

  return (
    <header className="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-4 shadow-sm md:px-8">
      <div className="flex items-center gap-4">
        {/* Sidebar Toggle (Mobile) */}
        <button
          type="button"
          onClick={onToggleSidebar}
          className="flex h-10 w-10 items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 lg:hidden"
        >
          <FontAwesomeIcon icon={faBars} className="text-lg" />
        </button>

        {/* Dynamic Logo from API */}
        <Link href="/" className="flex items-center gap-3 lg:hidden">
          <img
            src={logoSrc}
            alt="CourseEnglish"
            className="h-10 w-auto"
            loading="lazy"
          />
        </Link>
      </div>

      <div className="flex flex-1 items-center justify-end gap-3">
        <div className="relative hidden max-w-md flex-1 lg:block">
          <input
            type="search"
            placeholder="Search lessons, resources, or coaches"
            className="w-full rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-100"
          />
          <span className="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs font-normal text-slate-400">
            /
          </span>
        </div>

        {/* Avatar Dropdown */}
        <div className="relative" ref={dropdownRef}>
          <button
            type="button"
            onClick={() => setDropdownOpen(!isDropdownOpen)}
            className="flex items-center gap-3 rounded-full transition hover:opacity-80 focus:outline-none"
          >
            <div className="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 border-2 border-slate-100 text-sm font-normal text-white shadow-sm">
              {initials}
            </div>
          </button>

          {/* Dropdown Menu */}
          {isDropdownOpen && (
            <div className="absolute right-0 mt-2 w-48 origin-top-right rounded-xl bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none z-50">
              <div className="px-4 py-3 border-b border-slate-100">
                <p className="text-sm font-normal text-slate-900 truncate">{user?.name || "Student"}</p>
                <p className="text-xs text-slate-500 truncate">{user?.email}</p>
              </div>

              <Link
                href="/student/profile"
                className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
                onClick={() => setDropdownOpen(false)}
              >
                Edit Profile
              </Link>

              <button
                onClick={handleLogout}
                disabled={loggingOut}
                className="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 disabled:opacity-50"
              >
                {loggingOut ? "Signing out..." : "Logout"}
              </button>
            </div>
          )}
        </div>
      </div>
    </header>
  );
}
