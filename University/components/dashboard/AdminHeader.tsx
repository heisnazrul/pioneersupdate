"use client";

import { useState, useEffect } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBell, faSearch, faRightFromBracket, faUserShield } from "@fortawesome/free-solid-svg-icons";

export default function AdminHeader() {
    const [user, setUser] = useState<any>(null);

    useEffect(() => {
        const userStr = localStorage.getItem("auth_user");
        if (userStr) {
            try {
                setUser(JSON.parse(userStr));
            } catch (e) {
                console.error("Failed to parse user", e);
            }
        }
    }, []);

    const handleLogout = () => {
        if (confirm("Are you sure you want to sign out?")) {
            localStorage.removeItem("auth_token");
            localStorage.removeItem("auth_user");
            window.dispatchEvent(new Event('auth-update'));
            window.location.href = "/";
        }
    };

    return (
        <header className="bg-white border-b border-slate-100 h-20 px-8 flex items-center justify-between sticky top-0 z-10 shadow-sm">
            {/* Search / Title */}
            <div className="flex items-center gap-4">
                <div className="relative hidden md:block group">
                    <FontAwesomeIcon icon={faSearch} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-purple-600 transition-colors" />
                    <input
                        type="text"
                        placeholder="Search system..."
                        className="bg-slate-50 border border-slate-200 rounded-full pl-10 pr-4 py-2 w-64 text-sm focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-600/10 transition-all"
                    />
                </div>
            </div>

            {/* Right Side Actions */}
            <div className="flex items-center gap-6">
                {/* Notifications */}
                <button className="relative w-10 h-10 rounded-full bg-slate-50 hover:bg-purple-50 text-slate-500 hover:text-purple-600 flex items-center justify-center transition-all">
                    <FontAwesomeIcon icon={faBell} className="w-5 h-5" />
                    <span className="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                {/* Divider */}
                <div className="h-8 w-px bg-slate-200"></div>

                {/* Profile / Logout */}
                <div className="flex items-center gap-4">
                    <div className="text-right hidden md:block">
                        <div className="text-sm font-bold text-slate-900">{user?.name || "Admin User"}</div>
                        <div className="text-xs text-purple-600 uppercase font-bold tracking-wide">
                            {user?.role?.replace('_', ' ') || "Administrator"}
                        </div>
                    </div>

                    <div className="h-10 w-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center border-2 border-purple-50 shadow-sm">
                        <FontAwesomeIcon icon={faUserShield} className="w-5 h-5" />
                    </div>

                    <button
                        onClick={handleLogout}
                        className="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-full text-sm font-bold transition-colors ml-2"
                    >
                        <FontAwesomeIcon icon={faRightFromBracket} />
                    </button>
                </div>
            </div>
        </header>
    );
}
